<?php

namespace humhub\modules\calendar_extension\controllers;

use Yii;
use humhub\modules\admin\components\Controller;
use humhub\modules\calendar_extension\models\CalendarExtensionEntry;
use humhub\modules\calendar_extension\interfaces\ICal;
use DateTime;
use humhub\modules\calendar_extension\interfaces\ParsedICal;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
//            'result' => $events,
//            'models' => $models,
        ]);
    }

    public function actionSync ()
    {
        // TODO: write function as Ajax --> click on button will update ical-Entries with URL
        $url = 'http://mep24web.de/mep-web/public/calendar/UhUicl2vj3m3gtk8JOvtxk4SNOnLPLn26Tt6ALBtaqLw9U_IjgqPXDx46eJ8yQhv';
//        $url = 'https://calendar.google.com/calendar/ical/eo36l909ocj7tl0v7t4optahqg%40group.calendar.google.com/private-4547e43bd8fa532b99b05d1a4966e1be/basic.ics';

        $result = $this->formatICalToArray($url);
        $events = $this->formatResultArray($result);
        $models = $this->createModels($events);
        if (!isset($models['error']))
        {
            $this->checkAndSubmitModels($models);
//            return $this->htmlRedirect(Url::to('/calendar_extension/admin/index'));
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



    private function formatICalToArray($url)
    {
        $ical = new ParsedICal($url);
        $ical->hasEvents();

        if ($ical->hasEvents())
        {
            return $ical->getEvents();
        }
        else
        {
            return null;
        }
    }

    private function formatTimestampToDateTime($timestamp)
    {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        return $date->format('Y-m-d h:i:s');
//        return Yii::$app->formatter->asDatetime($timestamp);
    }

    private function formatResultArray($result)
    {
        $events = [];
        foreach($result as $key=>$subarray)
        {
            $subNewArr = array();
            foreach ($subarray as $key => $value)
            {
                switch ($key) {
                    case 'dtstamp':
                        $subNewArr[$key] = $this->formatTimestampToDateTime($value);
                        break;
                    case 'dtstart':
                        $subNewArr[$key] = $this->formatTimestampToDateTime($value);
                        break;
                    case 'dtend':
                        $subNewArr[$key] = $this->formatTimestampToDateTime($value);
                        break;
                    case 'created':
                        $subNewArr[$key] = $this->formatTimestampToDateTime($value);
                        break;
                    case 'last-modified':
                        $subNewArr[$key] = $this->formatTimestampToDateTime($value);
                        break;
                    case 'description':
                        $subNewArr[$key] = $value[0];
                        break;
                    case 'organizer':
                        // remove "-chars from organizer
                        $subNewArr[$key] = $onlyconsonants = str_replace('"', "", $value['CN']);
                        break;
                    default:
                        $subNewArr[$key] = $value;
                }
            }
            array_push($events, $subNewArr);
        }
        return $events;
    }

    private function createModels(Array $events)
    {
        $models = [];
        foreach ($events as $event)
        {
            try {
                $model = new CalendarExtensionEntry();
                $model->title           =   $event['summary'];
                $model->uid             =   $event['uid'];
                $model->dtstamp         =   $event['dtstamp'];
                $model->last_modified   =   $event['last-modified'];
                $model->start_datetime  =   $event['dtstart'];
                $model->end_datetime    =   $event['dtend'];
                $model->organizer       =   $event['organizer'];
                $model->description     =   $event['description'];
                $model->all_day         =   0;
                $models[] = $model;
            }
            catch (Exception $e) {
                $models['error'] = $e->getMessage();
            }
            return $models;
        }
    }

    private function checkAndSubmitModels(Array &$models)
    {
        // models is an array of CalendarExtensionEntry-Models
        foreach ($models as $key => $value)
        {
            // search for existing entries in db
            if (($result = CalendarExtensionEntry::find()->where(['uid' => $value->uid])->andWhere(['last_modified' => $value->last_modified])->one()) !== null) {
                // if found --> remove models from array
                unset($models[$key]);
            }
            else if (($result = CalendarExtensionEntry::find()->where(['uid' => $value->uid])->one()) !== null) {
                // check if uid exists and enty has been updated in ical
                $this->updateModel($result, $value);
            }
            else {
                // nothing found --> new entry
                $this->storeModel($value);
            }
        }
    }

    private function updateModel(CalendarExtensionEntry &$result, CalendarExtensionEntry $model)
    {
        // TODO: check if double entries exists!!
        $result->title              = $model->title;
        $result->uid                = $model->uid;
        $result->dtstamp            = $model->dtstamp;
        $result->last_modified      = $model->last_modified;
        $result->start_datetime     = $model->start_datetime;
        $result->end_datetime       = $model->end_datetime;
        $result->organizer          = $model->organizer;
        $result->description        = $model->description;
        $result->all_day            = $model->all_day;

        $result->save();
    }

    private function storeModel(CalendarExtensionEntry &$model)
    {
        // TODO: store new model
        $model->save();
    }

}
