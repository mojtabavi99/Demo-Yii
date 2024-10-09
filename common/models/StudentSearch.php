<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Student;

/**
 * StudentSearch represents the model behind the search form of `common\models\Student`.
 */
class StudentSearch extends Student
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'school_id', 'birthdate', 'created_at', 'updated_at'], 'integer'],
            [['name', 'lastname', 'national_id', 'field_of_study', 'grade', 'academic_year', 'status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Student::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('user');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'school_id' => $this->school_id,
            'birthdate' => $this->birthdate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'user.name', $this->name])
            ->andFilterWhere(['like', 'user.lastname', $this->lastname])
            ->andFilterWhere(['like', 'national_id', $this->national_id])
            ->andFilterWhere(['like', 'field_of_study', $this->field_of_study])
            ->andFilterWhere(['like', 'grade', $this->grade])
            ->andFilterWhere(['like', 'academic_year', $this->academic_year])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
