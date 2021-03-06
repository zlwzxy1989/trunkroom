とある会社からの面接課題です。
時間：2016.7.11 - 2016.7.14　昼は仕事ありますので作業は帰宅後行っています。

※この課題を受ける前はfuelphpというフレームワークを知りません。

■課題
<pre><code>
----------------------------------------------
＊開発環境
LAMP
	mysql : 5.5.28
	php 5.4.45
	fuelphp
----------------------------------------------
＊主旨
トランクルーム各企業(以下、各企業)が運営しているトランクルームの情報を
アグリゲーションサイトとして横断検索できるサイトを開発したい

・対象トランクルーム企業：2社
	https://www.oshiire.co.jp/
	http://www.quraz.com/
----------------------------------------------
＊仕組み概要
１．各企業の特定項目の情報をクローリングで抽出
	※各企業は個別にサイトが構築されており情報項目はバラバラに配置されており、
		かつ各企業サイトにより情報項目が異なります。
２．DBに格納　※取得データ別紙エクセル参照（下記）
	予め各サイトの共通項目をDB化してデータを格納
３．DBに格納したデータをcsvでDL
----------------------------------------------
No	取得データ項目	例：押入れ産業を元に記載　https://www.oshiire.co.jp/store/store_528.html
1	サイト名	押入れ産業
2	店舗名	RSS新築西通り店 (ベア・ロジコ株式会社)
3	住所(アクセス含む)	〒990-0041 山形県山形市緑町1-8-11　カサデロンダ緑町1F
4	電話番号	0120-372-110
5	説明文	"山形店のレンタルスペースは日本三大植木市の一つ薬師祭でにぎわう新築西通りに面し、2013年12月12日から対面通行となりさらにご利用しやすくなりました。
とても明るい店内で、自分に合った収納空間として24時間お好きな時間に出し入れが可能です。
広い駐車場、清潔な館内・安心安全なセキュリティ完備

＜お客様がご利用を決めたポイント＞
・正面がガラス張りになっているので店内が明るくて安心。
・1階フロアーなので荷物の出し入れが楽にできる。
・駐車場が広くて利用しやすい。
・通りに面しているので安心。
などのお声を頂いています。
見学も出来ますので、お気軽にお問合せ下さい。"
6	画像URL(全て取得)	https://www.oshiire.co.jp/uploads/2011/07/ce17f5e5023be242a702592eb5fca160-335x251.jpg
7	タイプ	A
8	広さ(約畳）	0.6
9	広さ(幅ｘ奥行ｘ高さ)	110x84x220
10	月額料金	4,500円
11	キャンペーン料金	
12	空き状況	利用中
13	料金表の下の説明文	"初期費用
・当月分と翌月分のご利用料
・保証金：ご利用料金の3ヶ月分
（ご解約の際に1ヶ月分を償却、2ヶ月分をご返金します）"

----------------------------------------------
</code></pre>
■対応
<pre><code>
・機能
    以下の機能を実装しました：
　１　quraz社の情報抽出、格納（oshiire社は対応のスクリプトファイルだけ作成、未実装）
　２　全データをcsvでDL

・構築手順
    LAMP+fuelphp,プロジェクト解凍後、fuel\app\config\development\db.phpのDB関連変数を編集してください。

・操作手順
　１　データ抽出：CLIモードでプロジェクトのpublicフォルダーに入り、以下のコマンドを実行：
          php index.php cron/trunkroom/
　　　抽出ログはfuel\app\logs\年\月\日.phpに保存されます

　２　CSVダウンロード：以下のURLにアクセス：
          http://your.domain.com/search/download
          ダウンロードは自動的に始まります。

          ※your.domain.comをローカルのドメインに置換してください。

・フレームワークとプロジェクトコード
   画像を参照ください。緑の方は私が書いたものです。

​

・DB
　site        -- 会社テーブル
　shop        -- 店舗テーブル
　trunk_room  -- トランクルームテーブル

　trunk_room.status(空き状況),trunk_room.volume(広さ(幅ｘ奥行ｘ高さ))は今後検索のためINTタイプしにたいところですが、時間が限られてるのでVARCHARタイプにしました。他にも時間節約のためいくつの妥協がります。

・ファイル
　すべてはdataフォルダーに置いてあります。
  homework_data_add_structrue.sql  -- データベースのSQL（ストラクチャとデータ）
　homework_structrue.sql           --  データベースのSQL（ストラクチャのみ）
　downlowd.csv                     -- 出力したCSVデータ
  code.png                         -- プロジェクトの構成図
</code></pre>
