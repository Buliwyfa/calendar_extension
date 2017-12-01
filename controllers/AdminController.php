<?php

namespace humhub\modules\calendar_extension\controllers;


use Yii;
use yii\helpers\Url;
use humhub\modules\admin\components\Controller;
use humhub\modules\calendar_extension\models\ConfigForm;


/**
 * AdminController implements the CRUD actions for CalendarExtensionEntry model.
 */
class AdminController extends Controller
{
    /**
     * Lists config model for calendar extension.
     * @return mixed
     */
    public function actionIndex()
    {
        $form = new ConfigForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate() && $form->save()) {
            return $this->redirect(Url::to(['/admin/module/list']));
        }

        return $this->render('index', ['model' => $form]);

    }

}
