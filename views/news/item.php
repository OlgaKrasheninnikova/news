<div class="card mb-4 box-shadow" style="padding: 20px;">
    <div><h3><a href="<?=\yii\helpers\Url::toRoute(['news/view', 'id' => $model->id])?>"><?=$model->name?></a></h3></div>
    <img class="card-img-top"
         alt="Thumbnail" style="height: 225px; width: 100%; display: block;" src="<?=$imgPath . $model->image?>" data-holder-rendered="true">
    <div class="card-body">
        <p class="card-text"><?=$model->description?></p>
        <div class="d-flex justify-content-between align-items-center">
            <div class="btn-group">
                <a href="<?=\yii\helpers\Url::toRoute(['news/view', 'id' => $model->id])?>" class="btn btn-sm btn-outline-secondary">Подробнее</a>
            </div>
            <small class="text-muted"><?=$model->date?></small>
        </div>
    </div>
</div>