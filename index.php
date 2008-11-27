<?php

/*
 * Email Template - Frog CMS behaviour
 *
 * Copyright (c) 2008 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/
 */

Plugin::setInfos(array(
    'id'          => 'email_template',
    'title'       => 'Email template', 
    'description' => 'Provides mailer backend to your forms.', 
    'version'     => '0.1.0',
    'update_url'  => 'http://www.appelsiini.net/download/frog-plugins.xml',
    'website'     => 'http://www.appelsiini.net/'
));

Behavior::add('Email_template', 'email_template/email_template.php');
