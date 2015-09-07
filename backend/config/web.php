$config = [ ... ];

if (YII_ENV_DEV) {
$config['bootstrap'][] = 'gii';
$config['modules']['gii'] = 'yii\gii\Module';
}