<?php
/*
|--------------------------------------------------------------------------
| 利用可能な変数一覧
| 
| ランキングページの細かい仕様が現在（2013/10/28）不明．
| 
| 以下の変数はそれぞれ['view']と['favorite']を持っている
| 'daily'
| 'weekly'
| 'monthly'
| ['view']と['favorite']の中身は共通．それぞれ〇〇_rankingテーブルの情報そのまま．
|--------------------------------------------------------------------------
 */
?>

<div class="main-right">
	<p class="popular">Minista ランキング</p>
	<div class="minista-list">
		<div class="clearfix">
			<div class="minista-img">
				<img src="image/fb_jmec_500.jpg">
			</div><!-- minista-img -->
			<div class="minista-text">
				<a href=""><p class="minista-title">人間としての最低限</p></a>
				<span class="minista-view">1234 view</span>
				<span class="minista-stock">★ 12</span>
			</div><!-- minista-text -->
		</div><!-- clearfix -->
	</div><!-- minista-list -->
	<div class="top-pager">
		<ul class="pure-paginator">
			<li><a class="pure-button prev" href="#">&#171;</a></li>
			<li><a class="pure-button pure-button-active" href="#">1</a></li>
			<li><a class="pure-button" href="#">2</a></li>
			<li><a class="pure-button" href="#">3</a></li>
			<li><a class="pure-button" href="#">4</a></li>
			<li><a class="pure-button" href="#">5</a></li>
			<li><a class="pure-button next" href="#">&#187;</a></li>
		</ul>
	</div><!-- top-pager -->
</div><!-- main-right -->