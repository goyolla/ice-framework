<?php 
/*

Copyright [2015] [Thilina Hasantha (thilina.hasantha[at]gmail.com)]

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
	
 */

$moduleName = 'metadata';
define('MODULE_PATH',dirname(__FILE__));
include APP_BASE_PATH.'header.php';
include APP_BASE_PATH.'modulejslibs.inc.php';
?><div class="span9">
			  
	<ul class="nav nav-tabs" id="modTab" style="margin-bottom:0px;margin-left:5px;border-bottom: none;">
		<li class="active"><a id="tabCountry" href="#tabPageCountry">Countries</a></li>
		<li><a id="tabProvince" href="#tabPageProvince">Provinces</a></li>
		<li><a id="tabCurrencyType" href="#tabPageCurrencyType">Currency Types</a></li>
		<li><a id="tabNationality" href="#tabPageNationality">Nationality</a></li>
	</ul>
	 
	<div class="tab-content">
		<div class="tab-pane active" id="tabPageCountry">
			<div id="Country" class="reviewBlock" data-content="List" style="padding-left:5px;">
		
			</div>
			<div id="CountryForm" class="reviewBlock" data-content="Form" style="padding-left:5px;display:none;">
		
			</div>
		</div>
		<div class="tab-pane" id="tabPageProvince">
			<div id="Province" class="reviewBlock" data-content="List" style="padding-left:5px;">
		
			</div>
			<div id="ProvinceForm" class="reviewBlock" data-content="Form" style="padding-left:5px;display:none;">
		
			</div>
		</div>
		<div class="tab-pane" id="tabPageCurrencyType">
			<div id="CurrencyType" class="reviewBlock" data-content="List" style="padding-left:5px;">
		
			</div>
			<div id="CurrencyTypeForm" class="reviewBlock" data-content="Form" style="padding-left:5px;display:none;">
		
			</div>
		</div>
		<div class="tab-pane" id="tabPageNationality">
			<div id="Nationality" class="reviewBlock" data-content="List" style="padding-left:5px;">
		
			</div>
			<div id="NationalityForm" class="reviewBlock" data-content="Form" style="padding-left:5px;display:none;">
		
			</div>
		</div>
	</div>

</div>
<script>
var modJsList = new Array();

modJsList['tabCountry'] = new CountryAdapter('Country','Country');
modJsList['tabCountry'].setRemoteTable(true);

modJsList['tabProvince'] = new ProvinceAdapter('Province','Province');
modJsList['tabProvince'].setRemoteTable(true);

modJsList['tabCurrencyType'] = new CurrencyTypeAdapter('CurrencyType','CurrencyType');
modJsList['tabCurrencyType'].setRemoteTable(true);

modJsList['tabNationality'] = new NationalityAdapter('Nationality','Nationality');
modJsList['tabNationality'].setRemoteTable(true);

var modJs = modJsList['tabCountry'];

</script>
<?php include APP_BASE_PATH.'footer.php';?>      