<?php

class SiteController extends CController {
	public function actionIndex() {
//		$this->redirect('/test/');
    header('location: /test/', 302);
    Yii::app()->end();
	}
}