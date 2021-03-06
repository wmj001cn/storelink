<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
<title>丰味烧烤（阳光城市广场)</title>
<link rel="stylesheet" href="//unpkg.com/bootstrap/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="//unpkg.com/font-awesome@4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="main.css"/>
<link rel="stylesheet" href="mymain.css"/>
<script src="fwsk.js"></script>
<script src="//unpkg.com/vue/dist/vue.min.js"></script>
</head>
<body>
	<script type="text/x-template" id="modal-template">
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-header">
                        <slot name="header">
                        </slot>
                    </div>
                    <div class="modal-body">
                        <slot name="body">
                        </slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer">
                            <button class="btn btn-primary" @click="$emit('close')">确认</button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
    </script>
	<div class="container-fluid" id="app">
		<modal v-if="showModal" @close="showModal = false;">
		<h5 slot="header">选择需要几个色盅</h5>
		<template slot="body">
		<div>
			<b-form-group label="">
		    <b-form-radio-group id="radios2" v-model="order.diceCount" name="radioSubComponent">
		    <b-form-radio
				value="1">1 人</b-form-radio> <b-form-radio value="2">2 人</b-form-radio>
			<b-form-radio value="3">3 人</b-form-radio> <b-form-radio value="4">4 人</b-form-radio>
			<b-form-radio value="5">5 人</b-form-radio> <b-form-radio value="6">6 人</b-form-radio>
			<b-form-radio value="7">7 人</b-form-radio> <b-form-radio value="8">8 人</b-form-radio>
			</b-form-radio-group> </b-form-group>
		</div>
		</template> </modal>

		<modal v-if="showRemark" @close="showRemark=false;">
		<template slot="body">
		<div class="container">
			<div class="row">
				备注：<textarea type="textarea" v-model="order.remark" rows="4" cols="50"></textarea>
			</div>
		</div>
		</template>
		</modal>
		
		<div class="cate" v-if="showCat">
				<div class="first-cat" v-if="showCat">
    				<ul>
    					
    					<li @click="goCat('gd')" :class="{ active: active_el == 'gd'}">招牌菜</li>
    					<li @click="goCat('vegi')" :class="{ active: active_el == 'vegi'}">蔬菜</li>
    					<li @click="goCat('meat')" :class="{ active: active_el == 'meat'}">肉食</li>
    					<li @click="goCat('marine')" :class="{ active: active_el == 'marine'}">水产</li>
    					<li @click="goCat('liquid')" :class="{ active: active_el == 'liquid'}">酒水</li>
    				</ul>
				</div>
			</div>
			
		
		<div class="top"><div class="container-fluid topbar">
			<div class="row">
				
				<div class="col border-left" v-show="!order.isOut">
					<div id="show-modal" @click="showModal = true;showCart=0;"
						class="btn btn color-yellow" style="width: 100%;">
						色盅 <i class="fa fa-bell" aria-hidden="true"></i>
					</div>
				</div>
			
				<div class="col border-left">
				    
					<div @click="showRemark=!showRemark;showCat=false;" class="btn btn color-yellow" style="width: 100%;">有求必应留言 <i class="fa fa-envelope-open" aria-hidden="true"></i></div>
				</div>
				
				<div class="col manu border-left">
					<div @click="showCat=!showCat;active_el=''; $('#mask').hide();" class="btn" style="background-color: '#fff99'; width: 100%;">菜单  <i class="fa fa-bars" aria-hidden="true"></i></div>
				</div>
			</div>
			
		</div>
		
		
		<div class="pay">
			<div v-show="showPayQR" id="wxPayQR" style="">
				<h4>
					
					<p>
						共需支付 <span class="red">{{formatPrice(sumByKey(order.items))}} 元</span>,
						
					</p>
					<p class="red">先请长按以下二维码, 再从弹出的选项中选择 "识别图中二维码"<p>
				</h4>
				
				<div><img class="file-image payCode"
					src="https://s7.postimg.org/anrnjo2vv/image.jpg" style="height: 180px; width: 180px; opacity: 1"></div>
				<div>
				<button @click="submit">
					<span v-show="!receiveClass"> 确认已支付 </span> 
					<span v-show="receiveClass"> <i class="fa fa-spinner fa-spin fa-small" style="font-size: 24px"> </i>正在提交...
					</span>
				</button>
				<button @click="hasProblem">遇到问题</button>
				<hr/>
				<p>加我为好友<p>
				<img class="" src="https://s7.postimg.org/9y8v7gmyj/image.jpg" style="height: 100px; width: 100px; opacity: 1"></div>
			</div>
		   </div>
	    </div>
		<div class="middle">
			<div id="mask" @click="$('#mask').hide();showCart=false;showCat=false;"></div>
			<div class="content-wrapper">
				<div class="container-fluid mylist">
					<div v-for="item in items" class="row product items" v-bind:class="{ active: isActive }" @mouseenter="changeClass">
						<div class="col-4 " style="padding: 0; margin: 0">
							<img :src="toUrl(item.url)" class="product-image" style="width: 124.328px; height: 72px" alt="暂无图片" />
						</div>
						<div class="col-4 product-desc">
							<p>{{item.desc}}</p>
							<span class="price">￥{{formatPrice(item.price)}} / 份 </span>
						</div>
						<div class="col-4 control">
								 <span class="btn btn-primary add btn-circle fa fa-plus" data-type="minus" @click="onTap(item)"></span>
								 <span><input class="btn input input-sm input-qty" v-model="item.qty" disabled="disabled" /></span>
								 <span class="btn btn-danger btn-circle fa fa-minus" @click="decrease(item)"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<transition name="fade">
		<div class="order-detail" v-show="showCart">
			<div :class="receiveClass" class="detail">
				<table class="table table-fixed">
					<tbody>
						<tr v-for="(item, k) in order.items">
							<td class="addedCount">{{item.desc}}</td>
							<td><i class="fa fa-plus" @click="onTap(item)"> </i> <input
								class="input-qty" v-model="item.qty" style="height: 23.5px"
								@blur="inputQty(item)" /> <i class="fa fa-minus"
								@click="decrease(item)"> </i></td>
							<td>{{item.price}}</td>
							<td><i class="fa fa-times-circle-o red" style="font-size: 1.2em"
								@click="remove(item.sku)"> </i></td>
						</tr>
					</tbody>
				</table>
				<span class="dummy">{{dummy}} </span>
			</div>
		</div>
		</transition>
		<div class="bottom">
			<div class="container-fluid">
				 <div class="row">
					<div class="col">
						<button class="btn btn-default btn-full-width border-left">￥{{formatPrice(sumByKey(order.items))}}</button>
					</div>
					<div class="col cart">
						<button class="btn btn-full-width border-left" @click="showMyCart">我拣的菜({{sumByKeyOnly(order.items, 'qty')}})</button>
					</div>
					<div class="col">
						<button class="btn btn-full-width border-left" @click="gotoPay();showCart=false;$('#mask').hide();">落单上菜</button>
					</div>
				</div>
			</div>
		</div>


	</div>
</body>
<script src="//unpkg.com/jquery@3.2.1/dist/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script src="//unpkg.com/vue-select@latest"></script>
<script src="//unpkg.com/babel-polyfill@latest/dist/polyfill.min.js"></script>
<script src="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js"></script>
<script src="//unpkg.com/vue-toasted"></script>
<script src="vue-cookies.min.js"></script>
<script src="flyto.js"></script>
<script src="main.js"></script>
</html>