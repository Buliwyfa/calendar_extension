<?php

namespace humhub\modules\calendar_extension\controllers;


use humhub\modules\calendar_extension\SyncUtils;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use humhub\modules\space\models\Space;
use humhub\modules\content\models\Content;
use humhub\modules\calendar_extension\permissions\ManageCalendar;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendar;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntry;
use ICal\ICal;


class CalendarController extends ContentContainerController
{

    /**
     * @inheritdoc
     */
    public $hideSidebar = true;


    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($this->contentContainer instanceof Space && !$this->contentContainer->isMember()) {
                throw new HttpException(403, Yii::t('base', 'You need to be member of the space "%space_name%" to access this example!', ['%space_name%' => $this->contentContainer->name]));
            }
            return true;
        }

        return false;
    }

    /**
     * Lists all CalendarExtensionCalendar models.
     * @return mixed
     * @throws HttpException
     */
    public function actionIndex()
    {
        if (!$this->canManageCalendar()) {
            throw new HttpException(403, Yii::t('CalendarExtensionModule.base', 'You are not allowed to show External Calendar!'));
        }

        $models = CalendarExtensionCalendar::find()->contentContainer($this->contentContainer)->all();

        return $this->render('index', [
            'models' => $models,
            'contentContainer' => $this->contentContainer,
        ]);
    }

    /**
     * Displays a single CalendarExtensionCalendar model.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     */
    public function actionView($id)
    {

        if (!$this->canManageCalendar()) {
            throw new HttpException(403, Yii::t('CalendarExtensionModule.base', 'You are not allowed to show External Calendar!'));
        }

        $model = $this->findModel($id);

        if ($model !== null) {
            return $this->render('view', [
                'model' => $model,
                'contentContainer' => $this->contentContainer,
            ]);
        } else {
            return $this->redirect($this->contentContainer->createUrl('create', array('id' => $id)));
        }
    }

    /** TODO: validate and check for errors + better message
     * Ajax-method called via button to sync external calendars.
     * @param integer $id
     * @return mixed
     */
    public function actionSync($id)
    {
        $message = '';
        $calendarModel = CalendarExtensionCalendar::find()->contentContainer($this->contentContainer)->where(['calendar_extension_calendar.id' => $id])->one();

        if ($calendarModel) {
            $ical = SyncUtils::createICal($calendarModel->url);
            if (!$ical) {
                $message = Yii::t('CalendarExtensionModule.sync_result', 'Error while creating ical... Check if link is reachable.');
            }
            else {
                // add info to CalendarModel
                $calendarModel->addAttributes($ical);
                $calendarModel->save();

                // check events
                if ($ical->hasEvents()) {
                    // get formatted array
                    $events = $ical->events();

                    // create Entry-models without safe
                    $models = SyncUtils::getModels($events, $calendarModel);
                    $result = SyncUtils::checkAndSubmitModels($models, $calendarModel->id);
                    if (!$result) {
                        $message = Yii::t('CalendarExtensionModule.sync_result', 'Error while check and submit models...');
                    }
                    else {
                        $message = Yii::t('CalendarExtensionModule.sync_result', 'Sync successfull!');
                    }
                }
            }
        } else {
            $message = Yii::t('CalendarExtensionModule.sync_result', 'Calendar not found!');
        }

        return $this->renderAjax('result', [
            'message' => $message,
        ]);
    }

    /**
     * @return string
     */
    public static function actionCron()
    {
        $calendarModels = CalendarExtensionCalendar::find()->all();
        foreach ($calendarModels as $calendarModel) {
            if ($calendarModel) {
                $ical = SyncUtils::createICal($calendarModel->url);
                if (!$ical) {
                    return;
                }

                // add info to CalendarModel
                $calendarModel->addAttributes($ical);
                $calendarModel->save();

                // check events
                if ($ical->hasEvents()) {
                    // get formatted array
                    $events = $ical->events();

                    // create Entry-models without safe
                    $models = SyncUtils::getModels($events, $calendarModel);
                    $result = SyncUtils::checkAndSubmitModels($models, $calendarModel->id);
                    if (!$result) {
                        return;
                    }
                }
            } else {
                return;
            }
        }
        return true;
    }

    /**
     * Creates a new CalendarExtensionCalendar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws HttpException
     */
    public function actionCreate()
    {

        if (!$this->canManageCalendar()) {
            throw new HttpException(403, Yii::t('CalendarExtensionModule.base', 'You are not allowed to show External Calendar!'));
        }

        $model = new CalendarExtensionCalendar();
//        $model->title = Yii::$app->request->get('title');
//        $model->scenario = 'create';

        $model->content->setContainer($this->contentContainer);
//        $model->content->visibility = ($model->load(Yii::$app->request->post('public'))) ? Content::VISIBILITY_PUBLIC : Content::VISIBILITY_PRIVATE ;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            try {
                // load ical and parse it
                $ical = new ICal($model->url, array(
                    'defaultTimeZone' => Yii::$app->timeZone,
                ));
                // add info to CalendarModel
                $model->addAttributes($ical);
                $model->save();
            } catch (\Exception $e) {
                return $this->render('create', [
                    'model' => $model,
                    'message' => $e,
                    'contentContainer' => $this->contentContainer
                ]);
            }
            $model->content->visibility = ($model->public) ? Content::VISIBILITY_PUBLIC : Content::VISIBILITY_PRIVATE ;
            $model->save();
            return $this->redirect($this->contentContainer->createUrl('view', array('id' => $model->id)));
        } else {
            return $this->render('create', [
                'model' => $model,
                'contentContainer' => $this->contentContainer
            ]);
        }
    }


    /**
     * Updates an existing CalendarExtensionCalendar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdate($id)
    {
        if (!$this->canManageCalendar()) {
            throw new HttpException(403, Yii::t('CalendarExtensionModule.base', 'You are not allowed to show External Calendar!'));
        }

//        $model = $this->findModel($id);
        $model = CalendarExtensionCalendar::find()->contentContainer($this->contentContainer)->where(['calendar_extension_calendar.id' => $id])->one();

        if (!$this->canManageCalendar()) {
            throw new HttpException(403, Yii::t('CalendarExtensionModule.base', 'External Calendar not editable!'));
        }

//        if ($this->canAdminister()) {
//            $model->scenario = 'admin';
//        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($this->contentContainer->createUrl('view', array('id' => $model->id)));
        } else {
            return $this->render('update', [
                'model' => $model,
                'contentContainer' => $this->contentContainer
            ]);
        }
    }


    /**
     * Deletes an existing CalendarExtensionCalendar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     */
    public function actionDelete($id)
    {
        if (!$this->canManageCalendar()) {
            throw new HttpException(403, Yii::t('CalendarExtensionModule.base', 'You are not allowed to show External Calendar!'));
        }

//        $this->findModel($id)->delete();

        $model = CalendarExtensionCalendar::find()->contentContainer($this->contentContainer)->where(['calendar_extension_calendar.id' => $id])->one();

        if ($model === null) {
            throw new HttpException(404, Yii::t('base', 'Page not found.'));
        }

        if (!$this->canManageCalendar()) {
            throw new HttpException(403, Yii::t('CalendarExtensionModule.base', 'Permission denied. You have no administration rights.'));
        }

        $model->delete();

        return $this->redirect($this->contentContainer->createUrl('index'));
    }


    /**
     * Finds the CalendarExtensionCalendar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CalendarExtensionCalendar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = CalendarExtensionCalendar::find()->contentContainer($this->contentContainer)->where(['calendar_extension_calendar.id' => $id])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * check for existing dbEntries and sync to list
     * entries that are no longer in list --> delete from db
     * @param array $models
     * @param $cal_id
     */
    public static function checkAndSubmitModels(Array &$models, $cal_id)
    {
        $dbModels = CalendarExtensionCalendarEntry::find()->where(['calendar_id' => $cal_id])->all();
        $keepInDb = [];

        // models is an array of CalendarExtensionCalendarEntry-Models
        foreach ($models as $key => $value) {
            $exists = false;
            foreach ($dbModels as $dbModel) {
                // search for existing entries in db
                if ($dbModel->uid == $value->uid && $dbModel->last_modified >= $value->last_modified) {
                    // if found and timestamp lower/identical--> nothing to change
                    array_push($keepInDb, $dbModel);
                    unset($models[$key]);
                    $exists = true;
                } elseif ($dbModel->uid == $value->uid) {
                    // check if uid exists and enty has been updated in ical
                    $dbModel->updateByModel($value);
                    array_push($keepInDb, $dbModel);
                    $exists = true;
                }
            }
            if (!$exists) {
                $value->save();
            }

        }
        // remove arrays to keep in db
        foreach ($dbModels as $key => $val) {
            foreach ($keepInDb as $item) {
                if ($val === $item) {
                    unset($dbModels[$key]);
                }
            }
        }
        // finally delete items from db
        foreach ($dbModels as $model) {
            $model->delete();
        }
        unset($keepInDb);
        unset($dbModels);
    }

    /**
     * Checks the ManageEntry permission for the given user on the given contentContainer.
     *
     * Todo: After 1.2.1 use $entry->content->canEdit();
     *
     * @return bool
     */
    private function canManageCalendar()
    {
        return $this->contentContainer->permissionManager->can(ManageCalendar::class);
    }
}
