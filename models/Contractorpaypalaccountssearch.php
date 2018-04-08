<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contractorpaypalaccounts;

/**
 * Contractorpaypalaccountssearch represents the model behind the search form about `app\models\Contractorpaypalaccounts`.
 */
class Contractorpaypalaccountssearch extends Contractorpaypalaccounts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contractor_paypal_accounts_id', 'contractor_id'], 'integer'],
            [['paypal_id', 'added_on', 'main'], 'safe'],
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
        $query = Contractorpaypalaccounts::find();

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
            'contractor_paypal_accounts_id' => $this->contractor_paypal_accounts_id,
            'contractor_id' => $this->contractor_id,
            'added_on' => $this->added_on,
        ]);

        $query->andFilterWhere(['like', 'paypal_id', $this->paypal_id])
            ->andFilterWhere(['like', 'main', $this->main]);

        return $dataProvider;
    }
}
