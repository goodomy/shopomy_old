<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Certificationcontractor;

/**
 * Certificationcontractorsearch represents the model behind the search form about `app\models\Certificationcontractor`.
 */
class Certificationcontractorsearch extends Certificationcontractor
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['certification_contractor_id', 'contractor_id', 'service_type_id', 'total_experience_years', 'total_experience_month', 'i_date', 'i_by', 'u_by', 'u_date'], 'integer'],
            [['front_side_image', 'back_side_image', 'tb_xray_image', 'skin_test_image', 'is_active', 'is_deleted'], 'safe'],
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
        $query = Certificationcontractor::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'certification_contractor_id' => $this->certification_contractor_id,
            'contractor_id' => $this->contractor_id,
            'service_type_id' => $this->service_type_id,
            'total_experience_years' => $this->total_experience_years,
            'total_experience_month' => $this->total_experience_month,
            'i_date' => $this->i_date,
            'i_by' => $this->i_by,
            'u_by' => $this->u_by,
            'u_date' => $this->u_date,
        ]);

        $query->andFilterWhere(['like', 'front_side_image', $this->front_side_image])
            ->andFilterWhere(['like', 'back_side_image', $this->back_side_image])
            ->andFilterWhere(['like', 'tb_xray_image', $this->tb_xray_image])
            ->andFilterWhere(['like', 'skin_test_image', $this->skin_test_image])
            ->andFilterWhere(['like', 'is_active', $this->is_active])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }
}
