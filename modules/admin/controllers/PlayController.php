<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Team;
use Yii;
use app\modules\admin\models\Play;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PlayController implements the CRUD actions for Play model.
 */
class PlayController extends AppAdminController
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
     * Lists all Play models.
     * @return mixed
     */
    public function actionIndex()
    {
        //Создаём запрос в виде массива и используем жадную загрузку со связями! В итоге 14 запросов к базе данных вместо 41 - без массива
        //и 10 в ввиде массива.

        $dateToSearch = getDateTo_Search();

        $query = Play::find()->with('teamHome', 'teamAway', 'league')->andWhere(['>', 'created_at',$dateToSearch])->asArray()->indexBy('id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionChamp($id)
    {

        $dateToSearch = getDateTo_Search();

        //Выводим определённый чемпионат в админке через ActiveDataProvider
        $query = Play::find()->with('teamHome', 'teamAway', 'league', 'team')->andWhere(['>', 'created_at',$dateToSearch])->andWhere(['=', 'is_active', 1])->asArray()->where(['league_id' => $id]);
        var_dump($query);
        /** $page - устанавливаем переменную в пагинацию. По умолчанию, это 10 - матчей на одной странице
         Если же $id, который к нам приходит в качестве параметра 3 - то есть это Германия, то 9 матчей на стр-цу*/
        $page = 10;
        if ($id == 3){
            $page = 9;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $page
            ],
        ]);

        return $this->render('champ', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Play model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Play model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Play();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Play model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing Play model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id - id лиги из модели Team->team_league
     * Экшен для автоматической фильтрации команд в ActiveForm в админке: admin/view/play/_form.php при выборе лиги
     * принимаем $id лиги и в зависимости от него фильтруем домашную и гостевую команды
     */
    public function actionList_team($id)
    {

        $team_count = Team::find()->where(['team_league' => $id])->count();

        $team = Team::find()->where(['team_league' => $id])->all();
        if($team_count > 0){
            foreach($team as $value){
                echo "<option value='".$value->team_id."'>".$value->team_name."</option>";
            }
        }else{
            echo "<option>-</option>";
        }

    }

    /**
     * Finds the Play model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Play the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Play::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
