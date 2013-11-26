#Volcanus Validation

[![Latest Stable Version](https://poser.pugx.org/volcanus/validation/v/stable.png)](https://packagist.org/packages/volcanus/validation)
[![Build Status](https://travis-ci.org/k-holy/volcanus-validation.png?branch=master)](https://travis-ci.org/k-holy/volcanus-validation)
[![Coverage Status](https://coveralls.io/repos/k-holy/volcanus-validation/badge.png?branch=master)](https://coveralls.io/r/k-holy/volcanus-validation?branch=master)

あるオブジェクトのプロパティまたは配列の値に対して、型や文字数、値の大小などの妥当性を検証するためのライブラリです。
ライブラリ全体としてはまだ作成途中ですが、検証処理自体は独立したクラス(Checker)として実装しており、それなりに稼働実績があります。
（実はこのプロジェクトは、それらの検証処理にテストケースを完備するために開始したものです）

##チェッカー(Checker)

ライブラリに同梱されているデフォルトチェッカーは、check()メソッドで与えられた引数を検証し、妥当ではない場合にはVolcanus\Validation\Exception\CheckerExceptionインタフェースを実装した例外をスローします。

    use Volcanus\Validation\Context;
    use Volcanus\Validation\Checker\IntChecker;
    use Volcanus\Validation\Exception\CheckerException;
    use Volcanus\Validation\Exception\CheckerException\IntException;
    use Volcanus\Validation\Exception\CheckerException\MinValueException;
    use Volcanus\Validation\Exception\CheckerException\MaxValueException;

    $checker = new IntChecker();
    try {
        $checker->check('foo', array('min' => 1, 'max' => 10));
    } catch (IntException $e) {
        die('数値を入力してください');
    } catch (MinValueException $e) {
        die('1以上の数値を入力してください');
    } catch (MaxValueException $e) {
        die('10以下の数値を入力してください');
    }

デフォルトチェッカーは__invoke()メソッドを実装しており、コールバックとして扱うこともできます。

    $checker = new IntChecker();
    try {
        $checker($data['number'], array(
            'min' => 1,
            'max' => 10,
        ));
    } catch (CheckerException $e) {
        die('1から10までの数値を入力してください');
    }


##Contextクラス

Contextクラスはチェック対象のオブジェクトまたは配列を保持し、Context::check()メソッドによりプロパティまたは配列のキーを指定してチェックを行えます。

    $validation = new Context(array(
        'id'   => 10,
        'name' => '',
    ));
    $validation->check('id'  , 'notEmpty');
    $validation->check('id'  , 'int' , array('min' => 1, 'max' => 10));
    $validation->check('name', 'notEmpty');
    $validation->check('name', 'maxLength', array('length' => 10));

検証結果はcheck()メソッドの戻り値から取得できるほか、isValid()メソッドやisError()メソッドで結果をチェックすることもできます。

    if ($validation->isValid()) {
        // 正常時の処理
    } else {
        $errors = array();
        if ($validation->isError('id', 'notEmpty')) {
            $errors[] = 'IDを入力してください';
        }
        if ($validation->isError('id', 'int')) {
            $errors[] = 'IDが不正です';
        }
        if ($validation->isError('name', 'notEmpty')) {
            $errors[] = '名前を入力してください';
        }
        if ($validation->isError('name', 'maxLength')) {
            $errors[] = '名前が長すぎます';
        }
    }

デフォルトチェッカーでは、エラーを検出した場合、その後は同じ項目へのチェックをスルーします。
上記の例だと、name値はnotEmptyチェックでエラーとなり、maxLengthチェックは実行されません。

デフォルトチェッカー以外にも、registerChecker()メソッドで独自のチェック処理を登録して利用できます。

    $validation = new Context(array(
        'id'   => 0,
        'name' => '',
    ));
    $validation->registerChecker('int', function($value, $options = array()) {
        if (!ctype_digit(strval($value)) ||
            strcmp(strval($value), sprintf('%d', $value)) !== 0
        ) {
            return false;
        }
        if (isset($options[0]) && intval($value) < $options[0]) {
            return false;
        }
        if (isset($options[1]) && intval($value) > $options[1]) {
            return false;
        }
        return true;
    });
    $validation->registerChecker('notEmpty', function($value) {
        return (isset($value) && strlen($value) !== 0);
    });

    $validation->check('id'  , 'int', array(1, 10)); // false
    $validation->check('name', 'notEmpty'); // false

