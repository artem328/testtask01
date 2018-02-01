<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo lang('lang'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo lang('title'); ?></title>
    <?php // html layout from https://codepen.io/eliortabeka/pen/JXBJZL ?>
    <style type="text/css">
        html,
        body {
            margin: 0;
            padding: 0;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        *,
        *:before,
        *:after {
            box-sizing: border-box;
            outline: none;
        }

        body {
            background: #2956af;
            font-family: 'Arimo', sans-serif;
            background-size: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .url-simplifier {
            margin: 0 auto 0;
            flex-grow: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .url-simplifier__title {
            color: #fff;
        }

        .url-simplifier__alert {
            background: #004797;
            color: #fff;
            margin-top: 30px;
            border-radius: 6px;
            padding: 15px 25px;
            font-size: 14px;
            -webkit-transform: scale(0);
            transform: scale(0);
            -webkit-transition: top, -webkit-transform 0.3s;
            transition: top, -webkit-transform 0.3s;
            transition: top, transform 0.3s;
            transition: top, transform 0.3s, -webkit-transform 0.3s;
            top: -100%;
            position: fixed;
        }

        .url-simplifier__alert--shown {
            -webkit-transform: scale(1);
            transform: scale(1);
            top: 0;
        }

        .url-simplifier__alert input[readonly] {
            background: #acc7ff;
            color: #11256d;
            border: none;
            padding: 5px;
        }

        .field {
            width: 100%;
            height: 70px;
            flex-grow: 1;
            position: relative;
        }

        .field input {
            width: 100%;
            border-radius: 6px;
            height: 70px;
            border: 0;
            padding: 10px;
            padding: 20px 0 0 16px;
            font-size: 0;
            background: #1566BB;
            transition: background .3s ease;
            color: #ffffff;
        }

        .field input:focus {
            background: #2477ce;
            font-size: 23px;
        }

        .field input:focus::selection {
            background: rgba(188, 232, 255, 0.5);
        }

        .field input.active {
            background: #065CB7;
            font-size: 23px;
        }

        .field input,
        .field button {
            position: absolute;
            height: 70px;
        }

        .field button {
            background: rgba(0, 0, 0, 0.55);
            right: 0;
            border: 0;
            width: 115px;
            border-radius: 6px;
            font-size: 22px;
            cursor: pointer;
            transition: width .3s ease, background .3s ease, opacity .3s ease;
            opacity: 0;
            color: #065CB7;
            text-transform: uppercae;
            pointer-events: none;
        }

        .field button.active {
            color: #ffffff;
            background: #639EDB;
            opacity: 1;
            pointer-events: auto;
        }

        .field button.active:hover {
            background: #5E99D6;
        }

        .field button.full {
            width: 100%;
        }

        .field input:focus + label {
            font-size: 19px;
            transform: translate(16px, 11px);
            color: rgba(255, 255, 255, 0.7);
        }

        .field label {
            position: absolute;
            color: white;
            transform: translate(16px, 20px);
            transition: transform .3s ease, font-size .3s ease, color .3s .1s ease;
            font-size: 28px;
        }

        .field label.active {
            font-size: 19px;
            transform: translate(16px, 11px);
            color: rgba(255, 255, 255, 0.7);
        }

        .field input:focus + label + button {
            opacity: 1;
        }
    </style>
</head>
<body>
<div class="url-simplifier">
    <h1 class="url-simplifier__title"><?php echo lang('title_simplify'); ?></h1>
    <div class="field">
        <form method="post" action="<?php echo base_url('simplify'); ?>" id="url-simplify-form">
            <input id="url" type="text" name="url" autocomplete="off"/>
            <label id="url-label" for="url"><span><?php echo lang('enter_url'); ?></span></label>
            <button type="submit" id="submit-button"><?php echo lang('button_simplify'); ?></button>
        </form>
    </div>
    <div class="url-simplifier__alert" id="short-url-result">
        <p><?php echo lang('result_url'); ?>
            <input type="text"
                   readonly
                   value=""
                   id="short-url"
                   title="">
        </p>
    </div>
    <div class="url-simplifier__alert" id="error"><?php echo lang('error_generate_url'); ?></div>
</div>
<script type="text/javascript">
    (function (d) {
        var urlField = d.getElementById('url'),
            submitButton = d.getElementById('submit-button'),
            label = d.getElementById('url-label'),
            resultAlert = d.getElementById('short-url-result'),
            shortUrlField = d.getElementById('short-url'),
            form = d.getElementById('url-simplify-form'),
            error = d.getElementById('error'),
            displayAlert = function (url) {
                shortUrlField.value = url;
                resultAlert.classList.add('url-simplifier__alert--shown');
            },
            hideAlert = function () {
                resultAlert.classList.remove('url-simplifier__alert--shown');
                shortUrlField.value = '';
            },
            displayError = function () {
                error.classList.add('url-simplifier__alert--shown');
            },
            hideError = function () {
                error.classList.remove('url-simplifier__alert--shown');
            };

        urlField.addEventListener('keyup', function () {
            if (this.value) {
                if (!urlField.classList.contains('active')) {
                    urlField.classList.add('active');
                }

                if (!submitButton.classList.contains('active')) {
                    submitButton.classList.add('active');
                }

                if (!label.classList.contains('active')) {
                    label.classList.add('active');
                }

            } else {
                label.classList.remove('active');
                submitButton.classList.remove('active');
                urlField.classList.remove('active');
            }
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            hideError();
            hideAlert();

            var xhr = new XMLHttpRequest();

            xhr.open(this.method, this.action, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (4 !== xhr.readyState) {
                    return;
                }

                try {
                    var response = JSON.parse(xhr.responseText);
                    if (200 === xhr.status) {
                        if (response.success) {
                            displayAlert(response.short_url);
                        } else {
                            displayError();
                        }
                    } else {
                        displayError();
                    }
                } catch (e) {
                    displayError();
                }
            };

            xhr.send('url=' + encodeURIComponent(urlField.value));
        });
    })(document);
</script>
</body>
</html>