Amazon ses extension for Yii2
=============================
Extension for sending emails via amazon ses v4. (based on daniel-zahariev/php-aws-ses) 


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist waytonoway/mailer-ses-v4
```

or add

```
"waytonoway/mailer-ses-v4": "1.0.0"
```

to the require section of your `composer.json` file.


Usage
-----

To use this extension, you should configure it in the application configuration like the following:

```php
'components' => [
    ...
    'mail' => [
        'class' => 'waytonoway\ses\Mailer',
        'access_key' => 'Your access key',
        'secret_key' => 'Your secret key',
        'host' => 'email.us-east-1.amazonaws.com' // not required
    ],
    ...
],
```

To send an email, you may use the following code:

```php
Yii::$app->mail->compose('contact/html', ['contactForm' => $form])
    ->setFrom('from@domain.com')
    ->setTo($form->email)
    ->setSubject($form->subject)
    ->send();
```


To send an email with headers, you may use the following code:

```php
Yii::$app->mail->compose('contact/html', ['contactForm' => $form])
    ->setFrom('from@domain.com')
    ->setTo($form->email)
    ->setSubject($form->subject)
    ->setHeader('Precedence', 'bulk')
    ->setHeader('List-id', '<1>')
    ->setHeader('List-Unsubscribe', Url::to(['user/unsubscribe'], true))
    ->send();
```
