# mailform-php
## 前提条件
- サーバー環境になるべく依存せず、プログラム単体で動作完了できることを目的にしています。
- データはログ出力し、DBは利用しない形にしています。
- GoogleAnalyticsのコンバージョン設定をしやすくするために、完了画面やエラー画面はそれぞれ別ファイル名で扱っています。
- reCAPCHAの利用には、sitekey、seacretkeyの発行が必要です。

## ディレクトリ構成
```
リポジトリTOP
│
├ mailform .. メールフォーマット保存フォルダ
│　├ mailformat.txt .. メールフォーマット
│
├ index.html .. メール送信フォーム
├ contact.php .. メール送信処理
├ contact_end.php .. メール送信完了画面
├ contact_err.php .. メール送信エラー画面
```
## 動作概要
- index.htmlにて入力した項目をPOSTでcontact.phpで受け取り
- contact.phpにてメール送信を実行し、ログを出力
- 正常の場合は完了画面、エラーの場合はエラー画面に遷移
- フォームの必須チェックはindex.html内のjavascriptにて実施

## 編集箇所
- index.htmlのフォーム項目
- index.htmlのサイトキー設定
- contact.phpのメールタイトルや送信先アドレス
- contact.phpのシークレットキー設定
- mailform/mailformat.txtの送信項目や署名箇所

