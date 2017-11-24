<?php

namespace humhub\modules\calendar_extension\controllers;


use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use humhub\modules\admin\components\Controller;
use humhub\modules\calendar_extension\interfaces\ParsedICal;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendar;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntry;


/**
 * AdminController implements the CRUD actions for CalendarExtensionEntry model.
 */
class AdminController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CalendarExtensionCalendar models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $url = 'https://calendar.google.com/calendar/ical/eo36l909ocj7tl0v7t4optahqg%40group.calendar.google.com/private-4547e43bd8fa532b99b05d1a4966e1be/basic.ics';
//        $url = 'http://mep24web.de/mep-web/public/calendar/UhUicl2vj3m3gtk8JOvtxk4SNOnLPLn26Tt6ALBtaqLw9U_IjgqPXDx46eJ8yQhv';

//        $ical = new ParsedICal($url);
//        try {
//        $ical = new ICal($url, array(
//            'defaultSpan'           => 2,     // Default value
//            'defaultTimeZone'       => 'UTC',
//            'defaultWeekStart'      => 'MO',  // Default value
//            'skipRecurrence'        => false, // Default value
//            'useTimeZoneWithRRules' => false, // Default value
//        ));
//            } catch (\Exception $e) {
//        die($e);
//        }
//        $result = $ical->events();
//        $result = $ical;


        $dataProvider = new ActiveDataProvider([
            'query' => CalendarExtensionCalendar::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
//            'result' => $result,
        ]);
    }

    /**
     * Displays a single CalendarExtensionCalendar model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /** TODO: actually not used --> Modal to view & edit Calendar
     * Render a modal and send the model. Modal than renders the view.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     */
    public function actionModal($id)
    {
        $model = $this->findModel($id);

        if(!$model) {
            throw new HttpException(404);
        }

//        if(!$entry->content->canView()) {
//            throw new HttpException(403);
//        }

        return $this->renderAjax('modal', [
            'model' => $model,
            'editUrl' => '',
            'canManageEntries' => false,
        ]);
    }

    /** TODO: validate and check for errors + better message
     * Ajax-method called via button to sync external calendars.
     * @param integer $id
     * @return mixed
     */
    public function actionSync ($id)
    {
        $calendarModel = $this->findModel($id);

        if ($calendarModel)
        {
            try {
                $ical = new ParsedICal($calendarModel->url);
            } catch (\Exception $e) {
                die($e);
            }
            if ($ical->hasEvents())
            {
                // get formatted array
                $entryArray = $ical->getICalArray();

                // create Entry-models without safe
                $models = [];
                foreach ($entryArray as $item)
                {
                    $model = new CalendarExtensionCalendarEntry($item);
                    $model->calendar_id = $calendarModel->id;
                    array_push($models, $model);
                    unset($model);
                }
                $this->checkAndSubmitModels($models, $calendarModel->id);
            }
            $message = Yii::t('CalendarExtensionModule.base', 'Update successfull!');
        }
        else
        {
            $message = Yii::t('CalendarExtensionModule.base', 'Update failed!');
        }

        return $this->renderAjax('result', [
            'message' => $message,
        ]);
    }

    /**
     * Creates a new CalendarExtensionCalendar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CalendarExtensionCalendar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CalendarExtensionCalendar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CalendarExtensionCalendar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
        if (($model = CalendarExtensionCalendar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Todo: code a better way to send Calendar-model with array of Entry-Models
     * @param array $entryArray
     * @param $calendar_id
     * @return array
     */
    protected function getModels(array $entryArray, $calendar_id)
    {
        $temp = [];
        foreach ($entryArray as $item)
        {
            $model = new CalendarExtensionCalendarEntry($item);
            $model->calendar_id = $calendar_id;
            array_push($temp, $model);
            unset($model);
        }
        return $temp;
    }


//    // Todo: old --> won't delete no longer existing ical-entries
//    protected function checkAndSubmitModels(array &$models)
//    {
//        // models is an array of CalendarExtensionCalendarEntry-Models
//        foreach ($models as $key => $value) {
//            // search for existing entries in db
//            if (($result = $dbModels::find()->where(['uid' => $value->uid])->andWhere(['calendar_id' => $value->calendar_id])->andWhere(['>=', 'last_modified', $value->last_modified])->one()) !== null) {
////            if ($value->findByUidAndCalAndTs() !== null) {
//                // if found and timestamp lower/identical--> remove models from array
//                unset($models[$key]);
//            } else if (($result = CalendarExtensionCalendarEntry::find()->where(['uid' => $value->uid])->one()) !== null) {
////            else if ($result = $value->findByUidAndCal() !== null) {
//                // check if uid exists and enty has been updated in ical
////                $this->updateModel($result, $value);
//                $result->updateByModel($value);
//            } else {
//                // nothing found --> new entry
//                $value->save();
//            }
//        }
//    }


    /**
     * check for existing dbEntries and sync to list
     * entries that are no longer in list --> delete from db
     * @param array $models
     * @param $cal_id
     */
    protected function checkAndSubmitModels(Array &$models, $cal_id)
    {
        $dbModels = CalendarExtensionCalendarEntry::find()->where(['calendar_id' => $cal_id])->all();
        $keepInDb = [];

        // models is an array of CalendarExtensionCalendarEntry-Models
        foreach ($models as $key => $value)
        {
            $exists = false;
            foreach ($dbModels as $dbModel)
            {
                // search for existing entries in db
                if ($dbModel->uid == $value->uid && $dbModel->last_modified >= $value->last_modified) {
                    // if found and timestamp lower/identical--> nothing to change
                    array_push($keepInDb, $dbModel);
                    unset($models[$key]);
                    $exists = true;
                }
                elseif ($dbModel->uid == $value->uid) {
                    // check if uid exists and enty has been updated in ical
                    $dbModel->updateByModel($value);
                    array_push($keepInDb, $dbModel);
                    $exists = true;
                }
            }
            if (!$exists)
            {
                $value->save();
            }

        }
        // remove arrays to keep in db
        foreach ($dbModels as $key => $val)
        {
            foreach ($keepInDb as $item)
            {
                if ($val == $item) {
                    unset($dbModels[$key]);
                }
            }
        }
        // finally delete items from db
        foreach ($dbModels as $model)
        {
            $model->delete();
        }
        unset($keepInDb);
        unset($dbModels);
    }

}
