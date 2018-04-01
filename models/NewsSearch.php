<?php


namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NewsSearch represents the model behind the search form about News.
 */
class NewsSearch extends Model
{
    /** @var integer */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    public $date;

    /** @var int */
    public $is_active;


    /** @inheritdoc */
    public function rules()
    {
        return [
            'fieldsSafe' => [['id', 'name', 'description', 'is_active', 'date'], 'safe'],
            'dateDefault' => ['date', 'default', 'value' => null],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'id'              => Yii::t('news', '#'),
            'name'        => Yii::t('news', 'Заголовок'),
            'description'           => Yii::t('news', 'Описание'),
            'date'      => Yii::t('news', 'Дата'),
            'is_active'   => Yii::t('news', 'Активна ли'),
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = News::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $modelClass = $query->modelClass;
        $table_name = $modelClass::tableName();

        if ($this->date !== null) {
            $date = strtotime($this->date);
            $query->andFilterWhere(['between', $table_name . '.date', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', $table_name . '.name', $this->name])
              ->andFilterWhere(['like', $table_name . '.description', $this->description])
              ->andFilterWhere([$table_name . '.id' => $this->id])
              ->andFilterWhere([$table_name . 'is_active' => $this->is_active]);

        return $dataProvider;
    }
}
