<?php

/*
[クラス説明]
用途：各定数値の管理
備考：一括して記載できるアドレス、接続情報、文言もこちらに記載
  */
class Constant
{
    // 管理者向けメール
    const ADDRESS_FOR_ADMIN     = [
        "test" => ''
    ];

    // ユーザ向けメール
    const ADDRESS_FOR_USER     = [
        "test" => ''
    ];
    // 管理者向けメール件名
    const TITLE_FOR_ADMIN     = [
        "test" => 'ユーザから問い合わせがありました'
    ];
    // ユーザ向けメール件名
    const TITLE_FOR_USER     = [
        "test" => 'お問い合わせありがとうござます'
    ];

    // データベース設定
    const DB_CONNECT = [
        "DB_NAME"       => "contact_form",
        "DB_DSN"        => "mysql:charset=utf8;dbhost=localhost;dbname=contact_form",
        "DB_USER"       => "root",
        "DB_PASS"       => "",
        // "DB_OPTION"     => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]
    ];

    // nameタグ (項目必須)
    const REQUIRED   = [
        "last"                    =>  "性",
        "first"                   =>  "名",
        "last_kana"               =>  "セイ",
        "first_kana"              =>  "メイ",
        "sex"                     =>  "性別",
        "zip"                     =>  "郵便番号",
        "pref"                    =>  "都道府県",
        "city"                    =>  "市区町村",
        "street"                  =>  "番地",
        "phone"                   =>  "電話番号",
        "mail"                    =>  "メールアドレス",
        "cf_mail"                 =>  "確認用メールアドレス",
        "consultation_type"       =>  "ご相談種別",
        "dtl"                     =>  "お問い合わせ内容",
        "user_name"              =>  "ユーザ名",
        "user_password"          =>  "パスワード",
        "confirm_user_password"  =>  "確認用パスワード"
    ];

    // nameタグ (コンソール画面)
    const CONSOLE   = [
        "select"              =>  "表示",
        "insert"              =>  "追加",
        "update"              =>  "更新",
        "delete"              =>  "削除"
    ];


    // 都道府県名(項目)
    const PREF = [
        "北海道",
        "青森県",
        "岩手県",
        "宮城県",
        "秋田県",
        "山形県",
        "福島県",
        "茨城県",
        "栃木県",
        "群馬県",
        "埼玉県",
        "千葉県",
        "東京都",
        "神奈川県",
        "新潟県",
        "富山県",
        "石川県",
        "福井県",
        "山梨県",
        "長野県",
        "岐阜県",
        "静岡県",
        "愛知県",
        "三重県",
        "滋賀県",
        "京都府",
        "大阪府",
        "兵庫県",
        "奈良県",
        "和歌山県",
        "鳥取県",
        "島根県",
        "岡山県",
        "広島県",
        "山口県",
        "徳島県",
        "香川県",
        "愛媛県",
        "高知県",
        "福岡県",
        "佐賀県",
        "長崎県",
        "熊本県",
        "大分県",
        "宮崎県",
        "鹿児島県",
        "沖縄県"
    ];

    // ご相談種別
    const CONSULTATION_TYPE   = [
        "操作方法について",
        "技術的な内容",
        "それ以外の内容",
    ];

    // 性別
    const SEX   = [
        "男性",
        "女性",
    ];

}