<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Menu;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <script type="text/javascript"
                src="https://www.google.com/jsapi?autoload={
                'modules':[{
                'name':'visualization',
                'version':'1',
                'packages':['corechart']
                }]
        }"></script>
    </head>
    <body>

        <?php $this->beginBody() ?>
        <div class="wrap">
            <div class="header_wrapper">
                <div class="header">
                    <?php //echo Html::a(Html::img(Url::to('@web/images/primate-profiler-logo.png'), ['width'=>171, 'height'=>106]), Yii::$app->homeUrl);?>
                </div>
            </div>
            <?php
            NavBar::begin([
                'brandLabel' => Html::img(Url::to('@web/images/primate-profiler-logo.png'), ['width' => 171, 'height' => 106]),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $navigation_items = array();
            $navigation_items[] = ['label' => 'Home', 'url' => ['/site/index']];
            $navigation_items[] = ['label' => 'About', 'url' => 'http://www.primateprofiler.com/about-us/'];
            $navigation_items[] = ['label' => 'Contact', 'url' => 'http://www.primateprofiler.com/contact-us/'];
            
            $manager_text = 'Role: User';
            
            if (!Yii::$app->user->isGuest) {
                if (Yii::$app->user->identity->is_manager == 1) {
                    $navigation_items[] = ['label' => 'Create User', 'url' => ['/users/create']];
                    $navigation_items[] = ['label' => 'List Users', 'url' => ['/users/list']];
                    $manager_text = 'Role: Manager';
                }
                $navigation_items[] = ['label' => 'My Preferences', 'url' => ['/users/myprofile']];
                $navigation_items[] = ['label' => 'Logout (' . Yii::$app->user->identity->first_name .' '.$manager_text. ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']];
            } else {
                $navigation_items[] = ['label' => 'Login', 'url' => ['/site/login']];
            }

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $navigation_items,
            ]);
            NavBar::end();
            ?>
            <div class="breadcrumb_wrapper">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
            </div>
            <?php
            if (Yii::$app->user->isGuest) {
                $guest_class = ' guestClass';
            } else {
                $guest_class = '';
            }
            ?>
            <div class="container<?php echo $guest_class; ?>">                
                <div class="container">
                    <div class="sidebar">
                        <?=
                        Menu::widget(['items' => yii::$app->controller->availableApps,
                            'options' => ['class' => 'group-items']]);
                        ?>
                    </div>
                    <div class="content">
                        <?= $content ?>
                    </div>
                </div>



            </div>
        </div>

        <!--<footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>-->
        <?php
        require_once Yii::$app->basePath . '/views/layouts/footer.php';
        ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
