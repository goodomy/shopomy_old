<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cancellationreason;

/**
 * Cancellationreasonsearch represents the model behind the search form about `app\models\Cancellationreason`.
 */
class Cancellationreasonsearch extends Cancellationreason
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'i_by', 'u_by'], 'integer'],
            [['name', 'role', 'is_active', 'is_deleted', 'i_date', 'u_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Cancellationreason::find();
        $query->where(['is_deleted'=>'N']);

        $session = Yii::$app->session;
        $size = $session->get('user.size');

        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination'=>['pagesize'=> isset($size)?$size:5],
                'sort' => ['defaultOrder' => ['id' => SORT_DESC],'attributes'=>['id','u_date'] ],
        ]);

        if(isset($params['Cancellationreasonsearch']['status']) && $params['Cancellationreasonsearch']['status']!=null){
            $status=$params['Cancellationreasonsearch']['status'];
            $query->andFilterWhere(['like', 'is_active', $status]);
        }

        if(isset($params['Cancellationreasonsearch']['role']) && $params['Cancellationreasonsearch']['role']!=null){
            $role=$params['Cancellationreasonsearch']['role'];
            $query->andFilterWhere(['like', 'role', $role]);
        }

        if(isset($params['Cancellationreasonsearch']['keyword']) && $params['Cancellationreasonsearch']['keyword']!=null)
        {
            $keyword=$params['Cancellationreasonsearch']['keyword'];
            $query->andFilterWhere([
                'or',
                ['like', 'name', $keyword],
            ]);
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'i_by' => $this->i_by,
            'i_date' => $this->i_date,
            'u_by' => $this->u_by,
            'u_date' => $this->u_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'role', $this->role])
            ->andFilterWhere(['like', 'is_active', $this->is_active])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }
}
