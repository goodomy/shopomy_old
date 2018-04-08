<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cmspage;

/**
 * Cmspagesearch represents the model behind the search form about `app\models\Cmspage`.
 */
class Cmspagesearch extends Cmspage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'i_by', 'u_by'], 'integer'],
            [['title', 'content', 'is_active', 'is_deleted', 'i_date', 'u_date'], 'safe'],
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
        $query = Cmspage::find();
        $query->where(['is_deleted'=>'N']);
        
        $session = Yii::$app->session;
        $size = $session->get('user.size');
        
         $dataProvider = new ActiveDataProvider([
        		'query' => $query,
        		'pagination'=>['pagesize'=> isset($size)?$size:5],
        		'sort' => ['defaultOrder' => ['id' => SORT_DESC],'attributes'=>['id','u_date'] ],
        ]);

        if(isset($params['Cmssearch']['status']) && $params['Cmssearch']['status']!=null){
            $status=$params['Cmssearch']['status'];
            $query->andFilterWhere(['like', 'is_active', $status]);
        }

        if(isset($params['Cmssearch']['role']) && $params['Cmssearch']['role']!=null){
            $role=$params['Cmssearch']['role'];
            $query->andFilterWhere(['like', 'role', $role]);
        }

        if(isset($params['Cmssearch']['keyword']) && $params['Cmssearch']['keyword']!=null)
        {
            $keyword=$params['Cmssearch']['keyword'];
            $query->andFilterWhere([
                'or',
                ['like', 'title', $keyword],
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

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'is_active', $this->is_active])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }
}
