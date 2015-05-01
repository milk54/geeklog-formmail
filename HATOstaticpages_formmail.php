// +---------------------------------------------------------------------------+
// | SubFolderGeeklogCopySystem
// +---------------------------------------------------------------------------+
// | Copyright (C) 2014 by the following authors:
// | Authors    : Hiroshi Sakuramoto - hiro AT winkey DOT jp
// | Coordinate : IVY WE CO., LTD - www.ivywe.co.jp
// | Version: 1.0.0
// +---------------------------------------------------------------------------+
global $_CONF,$_USER,$_PLUGINS,$_SCRIPTS; // Geeklog変数
global $_fmhelppageurl,$_fmtblcolwidth,$_fmtokenttl; // FormMail変数
if (!defined('XHTML')) define('XHTML', ' /');

// --[[ 初期設定 ]]------------------------------------------------------------
//  静的ページPHPを作成する時に入力したIDを入れてください。
//  (wikiドキュメントのサンプル例だと'formmail')
$pageid = 'contact_install';
# ヘルプドキュメント用静的ページID
#    ※ヘルプ無しにするなら空文字にする
$helppageid = 'helpformmail';
# ヘルプドキュメント用URL
// ※直接ドキュメントファイルを用意するならそのURLを記載
$_fmhelppageurl='';


# 問合せを管理者へ通知の設定
#    複数のE-mailはカンマ(,)で区切りで指定する(スペース等はあけない)
#      例) 'info@hoge.com,admin@page.com'
#    特定の入力項目に応じて送り先を変える
#    ※この方法を利用する時は必ず $owner_email_item_name を指定してください。
#      例) 'AAA=info@hoge.com,BBB=admin@page.com'
$owner_email=$_CONF['site_mail'];
//$owner_email='hiroron@hiroron.com';

# 管理者Emailを入力項目から選択する項目名
//  ※送り先を変える指定をしたら先頭の#を削除してください。(コメントをはずします)
#$owner_email_item_name = 'q_mail_to';

# メール送信者E-mail
$email_from = $_CONF['site_mail'];
#Geeklog1.5から，noreplyを指定できます。
#$email_from = $_CONF['noreply_mail'];

# 問合せ者のメールアドレスの項目名
$email_input_name = 'q_mail';

# メール一致チェック項目指定
#   メール確認でどちらも同じものを入力 というname属性を(=)で区切る(スペース等はあけない)
#     例) 'email=reemail'
$essential_email = 'q_mail=q_mail_re';

# メールアドレスチェック項目指定
#   入力された値がメールアドレスとして正しいかチェックをする
#   INPUTタグの name属性の値をカンマ(,)区切りで指定する(スペース等はあけない)
#     例) 'email,reemail'
$propriety_email = 'q_mail,q_mail_re';

# テーブルの項目名のwidth
$_fmtblcolwidth='25%';

# CSRF対策のTokenの有効時間(秒)
$_fmtokenttl = 1800;
# Refererチェック (CSRF対策)  チェックしない:0 チェックする:1
$_spreferercheck = 1;
# Refererエラーのメッセージ
$_spreferererrormsg = '<p class="error">サイト外からのアクセスは禁止されています。</p>';


# ログイン済みならユーザ情報を利用する指定
#   ログインユーザーの名前やメールアドレスを利用
#
$username = ''; $user_email = '';
if (!COM_isAnonUser()) {
    $username = isset($_USER['fullname']) ? $_USER['fullname'] : $_USER['username'];
    $user_email = $_USER['email'];
}

# CSVファイルに保存
#   指定方法 保存しない: 0 , 保存する(カンマ区切り): 1 , 保存する(タブ区切り): 2
$save_csv = 1;

# CSVファイル保存場所 (直接入力時は最後にスラッシュ必須)
$save_csv_path = $_CONF['path_data'];

# CSVファイル名
$save_csv_name = 'contact_install.csv';

# CSVファイル保存の文字コード
#   文字コード変換をしない場合は '' と指定してください。
#   機能がOFFになります。（文字化けするようなら機能を''で
#   OFFにして別途フリーの文字変換ツールなどをご利用ください）
# 注意) mb_convert_encodingで使える文字コードを指定してください
#   例) UTF-8, SJIS, EUC-JP, JIS, ASCII
$save_csv_lang = 'UTF-8';

# 全角を半角に変換する項目名(英数字、スペース、カタカナ、ひらがな)
#   入力された値を自動で変換する項目を指定
#   INPUTタグの name属性の値をカンマ(,)区切りで指定する(スペース等はあけない)
$zentohan_itemname = 'q_phone,q_code1_1,q_code2_1,q_code3_1,q_code1_2,q_code2_2,q_code3_2,q_code1_3,q_code2_3,q_code3_3';

# カタカナの半角をカタカナの全角に変換する項目名
#   入力された値を自動で変換する項目を指定
#   INPUTタグの name属性の値をカンマ(,)区切りで指定する(スペース等はあけない)
$kana_hantozen_itemname = 'q_kana_1,q_kana_2';

# ひらがなをカタカナに変換する項目名
#   入力された値を自動で変換する項目を指定
#   INPUTタグの name属性の値をカンマ(,)区切りで指定する(スペース等はあけない)
$kana_hiratokana_itemname = 'q_kana_1,q_kana_2';

# 遷移の項目名
$seni_items = array('input' => '情報入力', 'confirm' => '入力項目確認', 'finish' => '入力完了');

# 必須入力の文字列
$required_string = '<span class="spf_required">*必須</span>';

# ==日付関係==
#   下記JavaScriptカレンダーでの日付表記 http://jqueryui.com/datepicker/#date-formats
$date_js_cal = "yy/mm/dd";
#   メールに記載される受付日時表記
#     phpのdateのものがすべて使えます http://www.php.net/manual/en/function.date.php
$date_mail = 'Y年m月d日H:i';
#   csv書き出し時、1列目に記載される日時表記
#     phpのdateのものがすべて使えます http://www.php.net/manual/en/function.date.php
$date_csv = 'Y/m/d H:i';

# カレンダー表示 jqueryui datepicker http://jqueryui.com/datepicker/
#   ※使わない場合はJSLIB;までコメントアウトしてください。
$jslib_datepicker = <<<JSLIB
$(function() { $.datepicker.setDefaults( $.datepicker.regional['ja'] ); $("#q_date1").datepicker({ dateFormat: '$date_js_cal', dayNamesMin: ['日','月','火','水','木','金','土'], monthNames: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'], showMonthAfterYear: true }); });
JSLIB;

#####
# 表示メッセージ
#####
$lang = array(
// { 完了HTML＆メールのメッセージ
  'receipt_admin' =>'管理者のみなさま'.LB.LB.$_CONF['site_name'].'サイトにおいて'.LB.'作成申込みがありました。'.LB.LB.'==========テストサイト作成申込み =========='.LB.'受付日時：'.date($date_mail),
  'receipt_user' =>'※本メールは、'.$_CONF['site_name'].'サイトより自動的に配信しています。'.LB.'このメールは送信専用のため、このメールにご返信いただけません。'.LB.'＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝'.LB.'テストサイト作成申込みありがとうございました。'.LB.LB.'========== テストサイト作成申込み内容 =========='.LB.'受付日時：'.date($date_mail),
  'subject_admin'=> '['.$_CONF['site_name'].']テストサイト作成申込み',
  'subject_user'=> '['.$_CONF['site_name'].']テストサイト作成申込みを受け付けました',
  'sign_admin'    => '-----------------------------------------'.LB.$_CONF['site_name'].LB.$_CONF['site_url'].LB.'-----------------------------------------',
  'sign_user'    => '-----------------------------------------'.LB.$_CONF['site_name'].LB.'URL：' . $_CONF['site_url'].LB.'-----------------------------------------',
// } 完了HTML＆メールのメッセージ
// { システムエラーのメッセージ
  'ownertransmiterror'=>'オーナーメール処理中、一部のアドレスでエラーが発生しましたが、処理を続けました。',
  'transmiterror'=>'処理中にエラーが発生しました。',
// } システムエラーのメッセージ
);



#####
# フォーム項目の設定
#####
$form_items = array(


  // < テーブル
  array('title' => 'インストール情報', 'table' => array(
    // < テーブル１行
    array('header' => 'サイトの情報',
 'valid_require' => $required_string, 'error_require' => 'サイトのURLが入力されていません。',
'valid_issite' => 'q_sitename', 'error_issite' => 'ディレクトリ名は使われています。', 'valid_minlen' => 'q_sitename=8', 'error_minlen' => '文字数は8桁以上にしてください。',
'valid_maxlen' => 'q_sitename=8', 'error_maxlen' => '文字数は8桁以下にしてください。',
 'data' =>
        array(
            array('input' => 'kanagawa.hatomarkweb.com/'),
            array('type' => 'text',
                  'name' => 'q_sitename',
                  'size' => '40',
            ),
            array('input' => '<br />サブディレクトリ名となる8文字（<a href="http://kanagawa.hatomarkweb.com/00000000/databox/category.php?code=mg_yokohamatsurumi&m=code&template=member_list&order=code&page=1&perpage=500" target="_blank" class="ext-link">会員コード</a>）を入力してください。'),
        ),
    ),
    // > テーブル１行

    // < テーブル１行
    array('header' => 'メールアドレス',
          'valid_require' => $required_string, 'error_require' => 'メールアドレスが入力されていません',
          'valid_hankaku' => 'q_mail', 'error_hankaku' => 'メールアドレスはすべて半角で入力してください',
'valid_maxlen' => 'q_mail=240', 'error_maxlen' => 'メールアドレスの文字数は240桁以下にしてください。',

          'data' =>
        array(
            array('type' => 'text',
                  'name' => 'q_mail',
                  'size' => '40',
                  'maxlen' => '240',
                  'class' => 'uk-width-1-1',
                  'value' => $user_email
            ),
            array('input' => '<br'.XHTML.'>'),
        ),
    ),
    // > テーブル１行
   ),
  ),
  // > テーブル
 
  // < 送信ボタン - 入力画面
  array('action' => 'input', 'data' =>
      array(
          array('string' => '    <div style="text-align: center;" class="text_center mt20 mb20">'),
          array('type' => 'submit',
                'name' => 'submit',
                'class' => 'button_form_next uk-button uk-button-large uk-button-primary',
                'value' => '入力項目確認画面へ'
          ),
          array('string' => '</div>'),
      ),
  ),
  // > 送信ボタン - 入力画面
  // < 送信ボタン - 確認画面
  array('action' => 'confirm', 'data' =>
      array(array('string' => '    <div style="text-align: center;" class="text_center mt20 mb20">'),
          array('type' => 'submit',
                'name' => 'goback',
                'class' => 'button_form_prev uk-button uk-button-large uk-button-primary',
                'value' => '戻る'
          ),
          array('string' => '　'),
          array('type' => 'submit',
                'name' => 'submit',
                'class' => 'button_form_next uk-button uk-button-large uk-button-primary',
                'value' => '送信する'
          ),
          array('string' => '</div>'),
      ),
  ),
  // > 送信ボタン - 確認画面
);


// --[[ 初期処理 ]]------------------------------------------------------------
# POSTデータを直接変換 (全角から半角へ、カタカナ半角からカタカナ全角へ)
if (!empty($zentohan_itemname)) { foreach (explode(',',$zentohan_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'askh'); } }
if (!empty($kana_hantozen_itemname)) { foreach (explode(',',$kana_hantozen_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'K'); } }
if (!empty($kana_hiratokana_itemname)) { foreach (explode(',',$kana_hiratokana_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'C'); } }
# データを保存用に加工
foreach ($_POST as $k => $v) {
    $fld_list[$k] = preg_replace('/,/', '，', $_POST[$k]);
    $fld_list[$k] = preg_replace('/"/', '”', $fld_list[$k]);
    $fld_list[$k] = preg_replace("/'/", "’", $fld_list[$k]);
    $fld_list[$k] = preg_replace('/`/', '‘', $fld_list[$k]);
    $fld_list[$k] = preg_replace('/;/', '；', $fld_list[$k]);
    $fld_list[$k] = preg_replace(preg_quote('#'.chr(92).'#'), '￥', $fld_list[$k]);
    $fld_list[$k] = COM_applyFilter($fld_list[$k]);
}
# CSVファイルのフルパス
$save_csv_file = $save_csv_path . $save_csv_name;
# idからurlを作成
if (!empty($pageid)) { $pageurl = COM_buildUrl($_CONF['site_url'].'/staticpages/index.php?page='.$pageid); }
if (empty($_fmhelppageurl) && !empty($helppageid)) { $_fmhelppageurl = COM_buildUrl($_CONF['site_url'].'/staticpages/index.php?page='.$helppageid); }
# CSRF
if (!empty($_POST) && !SECINT_checkToken()) { $m=isset($_POST[$email_input_name]) ? 'email='.$_POST[$email_input_name].' ' : ''; COM_accessLog("tried {$m}to staticpage({$pageid}) failed CSRF checks."); header('Location: '.$pageurl); exit; }

// --[[ 関数群 ]]---------------------------------------------------------------
if(!function_exists('_fmGetAction')){
function _fmGetAction ($err) {
    $buf = '';
    $action = COM_applyFilter($_POST['action']);
    if (!empty($action) && empty($err) && $action == 'input') { $buf = 'confirm'; }
    elseif (!empty($action) && empty($err) && $action == 'confirm') { $buf = empty($_POST['goback']) ? 'finish' : 'input'; }
    else { $buf = 'input'; }
    return $buf;
}

function _fmMkSeni ($items, $action) {
    $buf = '<ul>'.LB;
    foreach ($items as $key => $value) {
        if ($action == $key) {
            $buf .= '    <li class="on">'.$value.'</li>'.LB;
        } else {
            $buf .= '    <li>'.$value.'</li>'.LB;
        }
    }
    $buf .= '</ul>'.LB;
    return $buf;
}

function _fmPutiFilter($s) {
    $se = array('%','(',')',chr(92),chr(13).chr(10),chr(13),chr(10));
    $re = array('&#37;','&#40;','&#41;','&#92;','','','');
    return str_replace($se, $re, htmlspecialchars($s,ENT_QUOTES));
}

function _fmVld_isPhone($s) { return (preg_match('/^(?:[0-9'.chr(92).'+'.chr(92).'-'.chr(92).'s])+$/D',$s)) ? TRUE : FALSE; }
function _fmVld_isHankaku($s) { return (preg_match('/^(?:'.chr(92).'xEF'.chr(92).'xBD['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xEF'.chr(92).'xBE['.chr(92).'x80-'.chr(92).'x9F]|['.chr(92).'x20-'.chr(92).'x7E])+$/D',$s)) ? TRUE : FALSE; }
function _fmVld_isZenkaku($s) { return (preg_match('/(?:'.chr(92).'xEF'.chr(92).'xBD['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xEF'.chr(92).'xBE['.chr(92).'x80-'.chr(92).'x9F]|['.chr(92).'x20-'.chr(92).'x7E])+/D',$s)) ? FALSE : TRUE; }
function _fmVld_isEisuHan($s) { return (preg_match('/^(?:[0-9A-Za-z])+$/D',$s)) ? TRUE : FALSE; }
function _fmVld_isKanaZen($s) { return (preg_match('/^(?:'.chr(92).'xE3'.chr(92).'x82['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xE3'.chr(92).'x83['.chr(92).'x80-'.chr(92).'xB6])+$/D',$s)) ? TRUE : FALSE; }
function _fmVld_isHiraZen($s) { return (preg_match('/^(?:'.chr(92).'xE3'.chr(92).'x81['.chr(92).'x81-'.chr(92).'xBF]|'.chr(92).'xE3'.chr(92).'x82['.chr(92).'x80-'.chr(92).'x93])+$/D',$s)) ? TRUE : FALSE; }
function _fmVld_isNotKanaHan($s) { return (preg_match('/(?:'.chr(92).'xEF'.chr(92).'xBD['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xEF'.chr(92).'xBE['.chr(92).'x80-'.chr(92).'x9F])+/D',$s)) ? TRUE : FALSE; }

function _fmChkValidate ($mode, $datas, $errmsg, $attributes = '') {
    $msg = '';
    foreach ($datas as $data) {
        if (isset($data['type'])) {
            $name = $data['name'];
//<入力チェック>
switch ($mode) {
    // 必須チェック
    case 'require':
        if (empty($data['notrequire']) && empty($_POST[$name]) && $_POST[$name] != "0") { $msg = $errmsg; }
        break;
    // 一致チェック
    case 'equal':
        if (!empty($attributes)) {
            $es_emails = explode(',', $attributes);
            foreach ($es_emails as $es_email) {
                list($eq1,$eq2) = explode('=', $es_email);
                // 最初のキー かつ チェックするキーが存在
                if ($name == $eq1 && !empty($_POST[$eq2])) {
                    if ($_POST[$eq1] != $_POST[$eq2]) {
                        $msg = $errmsg;
                    }
                }
            }
        }
        break;
    // メールチェック
    case 'email':
        if (!empty($attributes)) {
            $pr_emails = explode(',', $attributes);
            foreach ($pr_emails as $pr_email) {
                if ($name == $pr_email) {
                    if (!COM_isemail($_POST[$name])) {
                        $msg = $errmsg;
                    }
                }
            }
        }
        break;
    // 数値チェック - 足して0以上
    case 'notzero':
            if (!empty($attributes)) {
                $values_key = explode(',', $attributes);
                foreach ($values_key as $val_key) {
                    // 最初のキーのときにチェック
                    if ($name == $val_key) {
                        $sum_val = 0;
                        foreach ($values_key as $chk_key) {
                            if (!empty($_POST[$chk_key])) {
                                $sum_val += $_POST[$chk_key];
                            }
                        }
                        if ($sum_val <= 0) {
                            $msg = $errmsg;
                            break;
                        }
                    }
                }
            }
            break;
    // 数値のみかチェック
    case 'numeric':
        if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !ctype_digit($_POST[$name])) { $msg = $errmsg; }
        break;
    // 電話番号かチェック
    case 'phone':
        if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !_fmVld_isPhone($_POST[$name])) { $msg = $errmsg; }
        break;
    // 半角チェック
    case 'hankaku':
        if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !_fmVld_isHankaku($_POST[$name])) { $msg = $errmsg; }
        break;
    // 全角チェック
    case 'zenkaku':
        if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !_fmVld_isZenkaku($_POST[$name])) { $msg = $errmsg; }
        break;
    // 半角英数字チェック
    case 'eisuhan':
        if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !_fmVld_isEisuHan($_POST[$name])) { $msg = $errmsg; }
        break;
    // 全角カタカナチェック
    case 'kanazen':
        if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !_fmVld_isKanaZen($_POST[$name])) { $msg = $errmsg; }
        break;
    // 全角ひらがなチェック
    case 'kanazen':
        if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !_fmVld_isHiraZen($_POST[$name])) { $msg = $errmsg; }
        break;
    // 半角カタカナ以外かチェック
    case 'notkanahan':
        if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && _fmVld_isNotKanaHan($_POST[$name])) { $msg = $errmsg; }
        break;
    // 文字数チェック
    case 'maxlen':
        if ((!empty($_POST[$name]) || $_POST[$name] == "0")) {
            foreach (explode(',', $attributes) as $attr1) {
                list($name2,$max2) = explode('=',$attr1);
                if ($name === $name2) {
                    if ($max2 < mb_strlen($_POST[$name], 'UTF-8')) { $msg = $errmsg; }
                }
            }
        }
        break;
    // 最低文字数チェック
    case 'minlen':
        if ((!empty($_POST[$name]) || $_POST[$name] == "0")) {
            foreach (explode(',', $attributes) as $attr1) {
                list($name2,$min2) = explode('=',$attr1);
                if ($name === $name2) {
                    if ($min2 > mb_strlen($_POST[$name], 'UTF-8')) { $msg = $errmsg; }
                }
            }
        }
        break;
    // サイト名存在チェック
    case 'issite':
        if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && CUSTOM_SFGCS_issitename($_POST[$name])) {
            $msg = $errmsg;
        }
        break;
}
//</入力チェック>
        }
    }
    return $msg;
}

function _fmValidateLines ($lines) {
    $errmsg;
    foreach (array('require','equal','email','notzero','numeric','phone','hankaku','zenkaku','eisuhan','kanazen','hirazen','notkanahan','maxlen','minlen','issite') as $chk) {
        // 必須,一致,メール,画像認証,エラー のチェック
        if (isset($lines['valid_'.$chk])) {
            $errmsg = _fmChkValidate($chk, $lines['data'], $lines['error_'.$chk], $lines['valid_'.$chk]);
            // エラーがあれば配列に格納
            if ($errmsg) {
                break;
            }
        }
    }
    return $errmsg;
}

function _fmValidateItems ($items) {
    $errs;
    foreach ($items as $item) {
        // 各テーブル
        foreach ($item as $key => $value) {
            // １テーブル
            if ($key == 'table') {
                $action = _fmGetAction('');
                foreach ($value as $key2 => $value2) {
                    // テーブル１行
                    $errmsg = _fmValidateLines($value2);
                    if ($errmsg) { $errs[] = $errmsg; }
                }
            }
        }
    }
    return $errs;
}

function _fmValidate ($items) {
    $buf = '';
    $errs = _fmValidateItems($items);
    if (!empty($errs)) {
        $errmsg = '';
        foreach ($errs as $err) {
            $errmsg .= '    <li>'.$err.'</li>'.LB;
        }
        $buf = <<<END

<p class="error">入力エラーがありました。下記について再度ご確認の上、ご記入ください。</p>
<ol class="errorList">
$errmsg
</ol>
END;
    }
    return $buf;
}


function _fmMkTitle ($title) {
    global $_fmtblcolwidth;
    return <<<END

    <h3 class="uk-panel-title">$title</h3>
    <table class="formmail" cellspacing="0">
        <colgroup><col width="{$_fmtblcolwidth}" /></colgroup>
        <tbody>
END;
}

function _fmMkForm_Value ($name, $value) {
    return (empty($_POST[$name]) && $_POST[$name] != "0") ? $value : _fmPutiFilter($_POST[$name]);
}

function _fmMkForm_Radio_Checked (&$attributes) {
    $name = $attributes['name'];
    if ((!empty($_POST[$name]) || $_POST[$name] == "0")) {
        if (isset($attributes['checked'])) unset($attributes['checked']);
        if ($_POST[$name] == $attributes['value']) {
            $attributes['checked'] = 'checked';
        }
    }
}

function _fmMkForm_Input ($attributes, $hidden = false) {
    if ($hidden) {
        if ($attributes['type'] == 'radio' || $attributes['type'] == 'checkbox') {
            if ($attributes['value'] != $_POST[$attributes['name']]) return '';
        }
        $attributes['type'] = 'hidden';
    }
    if ($attributes['type'] == 'radio' || $attributes['type'] == 'checkbox') {
        _fmMkForm_Radio_Checked($attributes);
    } else {
        if ($attributes['type'] != 'submit') $attributes['value'] = _fmMkForm_Value($attributes['name'], $attributes['value']);
    }
    $buf = '<input';
    foreach ($attributes as $key => $value) {
        if ($key != 'not_confirm') { $buf .= ' '.$key.'="'.$value.'"'; }
    }
    $buf .= XHTML.'>';
    if ($hidden || $attributes['type'] == 'checkbox') {
        if ( !isset($attributes['not_confirm']) || ! $attributes['not_confirm'] ) { $buf .= ' ' . $attributes['value']; }
    }
    return $buf;
}

function _fmMkForm_Select_Options ($name, $attributes) {
    $buf = '';
    $selected = _fmMkForm_Value($name, $attributes['selected']);
    $values = explode(',', $attributes['values']);
    foreach ($values as $value) {
        list($k,$v) = explode('=',$value);
        if (empty($v)) $v = $k;
        if ($selected == $k) {
            $buf .= '<option selected="selected" value="'.$v.'">'.$k.'</option>';
        } else {
            $buf .= '<option value="'.$v.'">'.$k.'</option>';
        }
    }
    return $buf;
}

function _fmMkForm_Select ($attributes) {
    $buf = '<select';
    foreach ($attributes as $key => $value) {
        if ($key != 'options') {
            $buf .= ' '.$key.'="'.$value.'"';
        }
    }
    $buf .= '>';
    $buf .= _fmMkForm_Select_Options($attributes['name'], $attributes['options']);
    $buf .= '</select>';
    return $buf;
}

function _fmMkForm_Textarea ($attributes) {
    $attributes['value'] = _fmMkForm_Value($attributes['name'], $attributes['value']);
    $buf = '<textarea';
    foreach ($attributes as $key => $value) {
        if ($key != 'value') $buf .= ' '.$key.'="'.$value.'"';
    }
    $buf .= '>'.$attributes['value'].'</textarea>';
    return $buf;
}

function _fmMkForm_Item ($items, $action) {
    $buf = '';
    if ($action != 'input' && $items['type'] != 'submit' && $items['type'] != 'hidden') {
        $buf .= _fmMkForm_Input($items, true);
    } else {
        switch ($items['type']) {
            case 'text': $buf .= _fmMkForm_Input($items); break;
            case 'password': $buf .= _fmMkForm_Input($items); break;
            case 'hidden': $buf .= _fmMkForm_Input($items); break;
            case 'radio': $buf .= _fmMkForm_Input($items); break;
            case 'checkbox': $buf .= _fmMkForm_Input($items); break;
            case 'select': $buf .= _fmMkForm_Select($items); break;
            case 'textarea': $buf .= _fmMkForm_Textarea($items); break;
            case 'submit': $buf .= _fmMkForm_Input($items); break;
            case 'reset': $buf .= _fmMkForm_Input($items); break;
            case 'button': $buf .= _fmMkForm_Input($items); break;
        }
    }
    return $buf;
}

function _fmMkTable_Data ($datas, $action) {
    $buf = '';
    foreach ($datas as $data) {
        // １つのデータ
        if (!empty($data['type'])) {
            // フォーム
            $buf .= _fmMkForm_Item($data, $action);
        }
        else {
            // 文字列
            foreach ($data as $key => $value) {
                if ($key == 'string') {
                    $buf .= $value;
                } elseif ($key == $action) {
                    $buf .= $value;
                }
            }
        }
    }
    return $buf;
}

function _fmMkTable ($tables, $action) {
    global $_fmhelppageurl;
    $buf = '';
    foreach ($tables as $lines) {
        $errflg = '';
        $tdclass='';
        $buf .= LB .'            <tr>' . LB;
        $buf .= '                <th>';
        if (isset($lines['header'])) { $buf .= $lines['header']; }
        if (isset($lines['valid_require'])) { $buf .= $lines['valid_require']; }
        if (isset($lines['help']) && !empty($_fmhelppageurl) && $action == 'input') { $buf .= ' (<a href="javascript:void(0);" id="'.$lines['help'].'" class="tooltip">?</a>)'; }
        // エラーチェック
        if (!empty($_POST)) { $errflg = _fmValidateLines($lines); }
        if ($errflg) { $tdclass=' class="warning_bgc"'; }
        $buf .= '</th>'.LB;
        $buf .= '                <td'.$tdclass.'>';
        if (isset($lines['data'])) {
            $buf .= _fmMkTable_Data($lines['data'], $action);
        }
        $buf .= '</td>'.LB;
        $buf .= '            </tr>'.LB;
    }
    return $buf;
}

function _fmMkForm ($items, $action) {
    global $_fmtokenttl;
    $ttl = (isset($_fmtokenttl) && $_fmtokenttl > 1) ? $_fmtokenttl : 1800;
    $buf = '';
    foreach ($items as $item) {
        // 各テーブル
        if (!empty($item['table'])) {
            foreach ($item as $key => $value) {
                // １テーブル
                switch ($key) {
                    case 'title': $buf .= _fmMkTitle($value); break;
                    case 'table': $buf .= _fmMkTable($value, $action); break;
                }
            }
            $buf .= <<<END

        </tbody>
    </table>
END;
        } elseif (!empty($item['action'])) {         // 送信ボタン
            if ($item['action'] == $action) {
                $buf .= LB . '    <input type="hidden" name="action" value="' . $action . '"' . XHTML . '>';
                $buf .= LB . _fmMkTable_Data($item['data'], $action);
            }
        }
    }
    if (!empty($buf) && ($action=='input' || $action=='confirm')) { $buf.=LB.'    <input type="hidden" name="_glsectoken" value="'.SEC_createToken($ttl).'"'.XHTML.'>'; }
    return $buf;
}

function _fmMkCsv ($items, $level=0, $dupcheck=array()) {
    $ret = array();
    if ($level > 5) { return; } $level++;
    if (!empty($items['type']) && strtolower($items['type']) != 'submit' ) {
        if((!empty($items['not_csv']) && $items['not_csv']) || empty($items['name'])) { return; }
        if(strtolower($items['type']) == 'radio' && in_array($items['name'], $dupcheck)) { return; }
        return $items['name'];
    } else {
        if (!is_array($items)) { return; }
        foreach ($items as $i) {
            $name = _fmMkCsv($i,$level, $ret);
            if (!empty($name)) {
                if(is_array($name)) { $ret = array_merge($ret,$name); } else { $ret[] = $name; }
            }
        }
    }
    return $ret;
}

function _fmChkReferer ($pu,$err) {
    global $_CONF;  $msg = '';  $action = COM_applyFilter($_POST['action']);
    if (!isset($_SERVER['HTTP_REFERER'])) {
        if (!empty($_POST)) { $msg = '<p class="error">REFERERチェックが設定されていますが環境変数にREFERERがセットされていないためチェックできません。サイト管理者にご連絡ください。</p>'; }
    } elseif (!empty($action) && ($action=='input' || $action=='confirm')) {
        if (strpos($_SERVER['HTTP_REFERER'],$pu)===FALSE) {
            $msg = $err;
        }
    } elseif (strpos($_SERVER['HTTP_REFERER'],$_CONF['site_url'])===FALSE) {
        $msg = $err;
    }
    return $msg;
}
}

// Refererチェック
if (!empty($_spreferercheck) && $_spreferercheck = 1) {
    $valid = _fmChkReferer($pageurl,$_spreferererrormsg);
}

// エラーチェック
if (empty($valid) && !empty($_POST) && !empty($_POST['action'])) {
    $valid = _fmValidate($form_items);
}
$action = _fmGetAction($valid);



// --[[ 第1ステップ : フォーム表示(入力＆確認) ]]-------------------------------
if ($action == 'input' || $action == 'confirm') {
/**
* フォーム画面HTML { ここから
*/
    // JS
    if ($action == 'input') {
        $flag_version_2 = version_compare($_CONF['supported_version_theme'], '2.0.0', '>=');
        # JS tooltip
        if (!empty($_fmhelppageurl) && isset($_SCRIPTS)) {
            $_SCRIPTS->setJavaScript("var autocomplete_data = [];var glConfigDocUrl='{$_fmhelppageurl}';",true);
            $_SCRIPTS->setJavaScriptLibrary('jquery.ui.autocomplete');
            $_SCRIPTS->setJavaScriptLibrary('jquery.ui.tabs');
            if ($flag_version_2) {
                $_SCRIPTS->setJavascriptFile('formmail', '/javascript/admin.configuration.js');
            } else {
                $_SCRIPTS->setJavascriptFile('formmail', '/javascript/formmail.js');
            }
        }
        # JS datepicker
        if (!empty($jslib_datepicker) && isset($_SCRIPTS)) {
            $_SCRIPTS->setJavaScriptLibrary('jquery.ui.datepicker');
            $_SCRIPTS->setJavascript($jslib_datepicker,true);
        }
    }
    // 遷移
    $seni = _fmMkSeni($seni_items, $action);
    // 入力フォーム
    $form = _fmMkForm($form_items, $action);

$retval = "<h1>ハトマーク物件検索テストサイト作成申込み</h1>";
$retval .= CUSTOM_getStaticpage("-contact1");

    $retval .= <<<END

<div id="FORM" class="uk-width-medium-2-3">
$valid
<form name="subForm" method="post" action="{$pageurl}">
<div id="tabs">
$form
</div>
</form>
</div>


END;

$retval .= CUSTOM_getStaticpage("-contact2");


/**
* } ここまで フォーム画面HTML
*/



// --[[ 第2ステップ : 完了表示＆メール送信 ]]-----------------------------------
} elseif ($action == 'finish') {
/**
* 完了画面HTML { ここから
*/
    // 遷移
    $seni = _fmMkSeni($seni_items, $action);

$out_html = CUSTOM_getStaticpage("-contact1a");

    $out_html .= <<<END

<div class="uk-grid">
<div class="uk-width-2-3">

<div id="contact_thanks">
<p><strong>サイト作成を受け付けました。</strong></p>
<p>※お問い合わせ確認のメールを自動送信しました。<br />
メールが届かない場合は、ご登録のメールアドレスが間違っている可能性があります。<br />
その際は、お手数ですが再度お問い合わせください。</p>
</div>

</div>
END;

$out_html .= CUSTOM_getStaticpage("-contact2");

/**
* } ここまで 完了画面HTML
*/



    # <br /> を改行コードに変換
    foreach ($fld_list as $k => $v) { $fld_list[$k] = ereg_replace("<br />", LB, $fld_list[$k]); }
    $lang['sign_admin'] = ereg_replace("<br />", LB, $lang['sign_admin']);
    $lang['sign_user'] = ereg_replace("<br />", LB, $lang['sign_user']);

    # ひな形用意
    $flgm = CUSTOM_SFGCS_mkdir($fld_list['q_sitename']);
    $flgc = CUSTOM_SFGCS_copysite($fld_list['q_sitename']);
    $flgs = CUSTOM_SFGCS_strreplace($fld_list['q_sitename'],$fld_list['q_user'],$fld_list['q_mail']);
    $flgd = CUSTOM_SFGCS_sqlimport($fld_list['q_sitename']);
    $flgm = var_export($flgm, TRUE);
    $flgc = var_export($flgc, TRUE);
    $flgs = var_export($flgs, TRUE);
    $flgd = var_export($flgd, TRUE);

 if(strcmp($flgm,"true")==0){
    $flgm ="済";
}
 if(strcmp($flgc,"true")==0){
    $flgc ="済";
}
 if(strcmp($flgs,"true")==0){
    $flgs ="済";
}
 if(strcmp($flgd,"true")==0){
    $flgd ="済";
}

    // 入力内容
    $input4mail=<<<END
URL: http://kanagawa.hatomarkweb.com/{$fld_list['q_sitename']}/
ユーザー名: admin
パスワード: hatomark2015
※直ちにパスワードを設定してください。
http://kanagawa.hatomarkweb.com/{$fld_list['q_sitename']}/users.php?mode=getpassword
ユーザー情報変更: http://kanagawa.hatomarkweb.com/{$fld_list['q_sitename']}/usersettings.php
メールアドレス: {$fld_list['q_mail']}
ディレクトリ作成: {$flgm}
サイトコピー: {$flgc}
文字列置き換え: {$flgs}
DB作成: {$flgs}
END;

/**
* 送信メール内容 - 管理者 { ここから
*/
    $out_mail_admin = <<<END

{$lang['receipt_admin']}

$input4mail

{$lang['sign_admin']}
END;
/**
* } ここまで 送信メール内容 - 管理者
*/
/**
* 送信メール内容 - 入力者 { ここから
*/
    $out_mail_user = <<<END

{$lang['receipt_user']}

$input4mail

{$lang['sign_user']}
END;
/**
* } ここまで 送信メール内容 - 入力者
*/

    # メール送信
    $ownererr = false;
    $ownersend = false;
    $om_array = explode(',', $owner_email);
    $owner_mails = array_unique($om_array);  # 重複した値(メールアドレス)を削除
    if (!empty($owner_email_item_name)) {
        $selmail;
        foreach ($owner_mails as $v) {
            list($key, $mail) = explode('=', $v);
            if ($_POST[$owner_email_item_name] == $key) {
                $selmail = explode('|', $mail);
                break;
            }
        }
        $owner_mails = $selmail;
    }
    $owner_subject = $lang['subject_admin'];
    foreach ($owner_mails as $v) {
        $email1 = COM_mail( $v, "$owner_subject", $out_mail_admin, $email_from, false); # オーナーあてメール
        if (!$email1) { $ownererr = true; } else { $ownersend = true; }  # 送信/エラーのフラグをセット
    }
    # オーナーメール送信でエラーがあった場合
    if ($ownererr) {
        # 一部に送信できている場合
        if ($ownersend) {
            # エラーをログへ出力(一部へは配送されているのでユーザにエラー画面を出さない)
            COM_errorLog($lang['ownertransmiterror'], 1);
            $email1 = true;
        # 全員がエラーの場合
        } elseif (!$ownersend) {
            # 処理エラーとし、ユーザへのメールは送らない
            $email1 = false;
        }
    }
    if ($email1) {
        $usr_subject = $lang['subject_user'];
        $email2 = COM_mail( $fld_list[$email_input_name], "$usr_subject", $out_mail_user, $email_from, false); # 問合せ者へメール
    }
    if ($email1 && $email2) { # どちらの送信も成功したら
        # csv出力する
        if ($save_csv > 0) {
            $fldnames = _fmMkCsv($form_items);
            $delimiter = ',';
            if ($save_csv > 1) { $delimiter = chr(9); }
            $enclosure = '"';
            # CSV出力
            $str = '';
            $escape_char = chr(92);
            foreach ($fldnames as $n) {
                $v = empty($fld_list[$n]) ? '' : $fld_list[$n] ;
                if (strpos($v, $delimiter) !== false ||
                    strpos($v, $enclosure) !== false ||
                    strpos($v, chr(10)) !== false ||
                    strpos($v, chr(13)) !== false ||
                    strpos($v, chr(9)) !== false ||
                    strpos($v, ' ') !== false) {
                  $str2 = $enclosure;
                  $escaped = 0;
                  $len = strlen($v);
                  for ($i=0;$i<$len;$i++) {
                    if ($v[$i] == $escape_char) {
                      $escaped = 1;
                    } else if (!$escaped && $v[$i] == $enclosure) {
                      $str2 .= $enclosure;
                    } else {
                      $escaped = 0;
                    }
                    $str2 .= $v[$i];
                  }
                  $str2 .= $enclosure;
                  $str .= $str2.$delimiter;
                } else {
                  $str .= $v.$delimiter;
                }
            }
            $str = date($date_csv) . $delimiter . substr($str,0,-1);
            $str .= LB;
            if( !empty( $save_csv_lang ) ) {
                $str = mb_convert_encoding($str, $save_csv_lang);
            }
            $fp = fopen($save_csv_file, 'a');
            fwrite($fp, $str);  # CSV書き出し
            fclose($fp);
        }
        $retval = $out_html;
    } else {
        $retval = $lang['transmiterror']; # メール送信が失敗したら
    }
}
// 「PHPを実行」の場合
echo $retval;
// 「PHPを実行(return)」 の場合、上のechoをコメント(#)にして以下のreturnのコメントをはずしてください
# return $retval;
