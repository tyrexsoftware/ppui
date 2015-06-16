<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Button;

$organization = app\models\Organization::findOne(['organization_id' => \Yii::$app->user->identity->organization_id]);
$path = Yii::getAlias('@web');
$user_id = Yii::$app->user->identity->user_id;

$js = <<<JS
function  initialise() {

    $('table input[name="selection[]"], input[name="selection_all"]').change(function(event) {
        if (event.target.name == "selection_all") {

            if ($('table input[name="selection[]"]:checked').length > 0)
            {
                $("#massactiontabs").hide();
            } else if ($('table input[name="selection[]"]:checked').length == 0) {
                $("#massactiontabs").show();
            }

        } else {
            if ($('table input[name="selection[]"]:checked').length > 0)
            {
                $("#massactiontabs").show();
            } else {
                $("#massactiontabs").hide();
            }
        }
    });

}

$("#massaction").on("click",
        function() {

            var animals = [];
            $('table input[name="selection[]"]:checked').each(function(key, value)
            {
                animals[key] = $(this).val();

            });
            users = $('select[name="users[]"]').val()
            applications = $('select[name="applications[]"]').val()

            if (users == null) {
                alert('Please add at least one user');
            }
            if (applications == null) {
                alert('Please add at least one application');
            }

            if (applications.length >= 1 && users.length >= 1) {
                $.ajax({
                    type: "POST",
                    data: {applications: applications, users: users, animals: animals},
                    url: "$path/applications/addsyncanimals",
                    success: function(reply) {
                        if ('reload' == reply.jsaction)
                        {
                            $('.grid-view').yiiGridView('applyFilter');

                            $("#w0-pjax").on('pjax:complete', function() {
                                $("#massactiontabs").hide();
                                //$("#usersselect").val("$user_id").trigger("change");
                                $("#appsselect").val(null).trigger("change");
                                initialise()
                            });

                        }
                    }
                });
            }

        }
);
$("#w0-pjax").on('pjax:complete', initialise());

JS;
$this->registerJs($js);
if (Yii::$app->user->identity->is_manager) {

    $users = ArrayHelper::map($organization->usersByOrganization, 'user_id', 'first_name');
    echo '<label class="control-label">Users</label>';
    echo Select2::widget([
        'name' => 'users',
        'data' => $users,
        'value' => Yii::$app->user->identity->user_id,
        'options' => [
            'placeholder' => 'Select users ...',
            'multiple' => true,
            'id' => 'usersselect'
        ],
    ]);
}


$apps = ArrayHelper::map($organization->userapplications, 'appkey', 'appname');


echo '<label class="control-label">Applications</label>';
echo Select2::widget([
    'name' => 'applications',
    'data' => $apps,
    'value' => 'all',
    'options' => [
        'placeholder' => 'Select applications ...',
        'id' => 'appsselect',
        'multiple' => true
    ],
]);
?>
<br>
<?=
Button::widget([
    'label' => 'Update',
    'options' => ['class' => 'btn btn-primary', 'id' => 'massaction'],
]);

//
//echo '<pre>';
//print_r($organization->userapplications);
//echo '</pre>';





