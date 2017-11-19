<?php

namespace humhub\modules\calendar_extension\controllers;

use Yii;
use humhub\modules\admin\components\Controller;
use humhub\modules\calendar_extension\models\CalendarExtensionEntry;

use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
     * Lists all CalendarExtensionEntry models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CalendarExtensionEntry::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CalendarExtensionEntry model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionModal($id)
    {
        $entry = $this->findModel($id);

        if(!$entry) {
            throw new HttpException(404);
        }

//        if(!$entry->content->canView()) {
//            throw new HttpException(403);
//        }

        return $this->renderAjax('modal', [
            'meeting' => $entry,
            'editUrl' => '',
            'canManageEntries' => false,
        ]);
    }

    /**
     * Creates a new CalendarExtensionEntry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CalendarExtensionEntry();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CalendarExtensionEntry model.
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
     * Deletes an existing CalendarExtensionEntry model.
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
     * Finds the CalendarExtensionEntry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CalendarExtensionEntry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CalendarExtensionEntry::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }




    private function createEntry()
    {

    }

    private function syncEntry()
    {

    }

    private function deleteEntry()
    {

    }

    // ****************************************************************************************
    // ******** 2. Anlegen eines neuen Events --> jetzt in models/forms/CalendarEntryForm *****
    // ****************************************************************************************

    /*    public function createNew($contentContainer, $start = null, $end = null)
        {
            $this->entry = new CalendarEntry();
            $this->entry->content->container = $contentContainer;
            $this->is_public = ($this->entry->content->visibility != null) ? $this->entry->content->visibility : Content::VISIBILITY_PRIVATE;
            $this->timeZone = Yii::$app->formatter->timeZone;

            $defaultSettings = new DefaultSettings(['contentContainer' => $contentContainer]);
            $this->entry->participation_mode = $defaultSettings->participation_mode;
            $this->entry->allow_decline = $defaultSettings->allow_decline;
            $this->entry->allow_maybe = $defaultSettings->allow_maybe;

            // Translate from user timeZone to system timeZone note the datepicker expects app timezone
            $this->translateDateTimes($start, $end, $this->timeZone, $this->timeZone);
        }*/


    // *****************************************************************************************
    // ******** 1. Erstellen / Bearbeiten eines Kalendereintrags - aus EntryController.php *****
    // *****************************************************************************************

    /*    public function actionEdit($id = null, $start = null, $end = null, $cal = null)
        {
            if (empty($id) && $this->canCreateEntries()) {
                $calendarEntryForm = new CalendarEntryForm();
                $calendarEntryForm->createNew($this->contentContainer, $start, $end);
            } else {
                $calendarEntryForm = new CalendarEntryForm(['entry' => $this->getCalendarEntry($id)]);
                if(!$calendarEntryForm->entry->content->canEdit()) {
                    throw new HttpException(403);
                }
            }

            if (!$calendarEntryForm->entry) {
                throw new HttpException(404);
            }

            if ($calendarEntryForm->load(Yii::$app->request->post()) && $calendarEntryForm->save()) {
                if(empty($cal)) {
                    return ModalClose::widget(['saved' => true]);
                } else {
                    return $this->renderModal($calendarEntryForm->entry, 1);
                }
            }

            return $this->renderAjax('edit', [
                'calendarEntryForm' => $calendarEntryForm,
                'contentContainer' => $this->contentContainer,
                'editUrl' => $this->contentContainer->createUrl('/calendar/entry/edit', ['id' => $calendarEntryForm->entry->id, 'cal' => $cal])
            ]);
        }*/
}
