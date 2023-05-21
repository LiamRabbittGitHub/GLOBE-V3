jQuery(document).ready(function($) {
	jQuery('.fc-send').keyup(function() {
		var val = jQuery(this).val();
		jQuery('.fc-send').html(val);
	});

	jQuery('.fc-receive').keyup(function() {
		var val = jQuery(this).val();
		jQuery('.fc-receive').html(val);
	});

	jQuery('.fc-desc').keyup(function() {
		var val = jQuery(this).val();
		jQuery('.desc').html(val);
	});

	jQuery('.fc-send').on('change', function() {
		var val = jQuery(this).val();
		jQuery('.fc-send').html(val);
	});

	jQuery('.fc-receive').on('change', function() {
		var val = jQuery(this).val();
		jQuery('.fc-receive').html(val);
	});

	jQuery('.fc-desc').on('change', function() {
		var val = jQuery(this).val();
		jQuery('.desc').html(val);
		dealcard();
	});

	jQuery('.serial').change(function() {
		var val = jQuery(this).val();
	});

	jQuery('.preview-template').click(function() {
		jQuery('.serial-num').text('Serial Number-' + jQuery('#playerscards').val());
		jQuery('.main-template-hide').addClass('main-template-show');
		window.scrollTo(0, 0);
	});
	jQuery('.close-template').click(function() {
		jQuery('.main-template-hide').removeClass('main-template-show');
	});

	$('.page-id-581 .post-content').append(
		'<div class="mloader"><div class="lds-ripple"><div></div><div></div></div></div>'
	);

// 	jQuery(document).keydown('#modal', function(event) {
// 		// If Control or Command key is pressed and the S key is pressed
// 		// run save function. 83 is the key code for S.
// 		if (event.ctrlKey || event.metaKey) {
// 			jQuery('#model .modal').remove();
// 			event.preventDefault();
// 		}
// 	});
});

var total = 9;
var mapObject = false;
var handleClick = false;
var infowindow,
	marker,
	markers = [],
	i,
	globeZoom = 2.5,
	zoomInterval;

function dealcard() {
	total += Math.random();
	let str_a = total.toString();
	let result = Number(str_a.slice(0, 9));
	document.getElementById('playerscards').value = result;
}

function screenshot() {
	var imgUrl = document.getElementsByClassName('main-img')[0].src;
	var receive = document.getElementsByClassName('fc-receive-left')[0].textContent;
	var sender = document.getElementsByClassName('fc-send-left')[0].textContent;
	var desc = document.getElementsByClassName('desc')[0].textContent;
	var lstxt = document.getElementsByClassName('fc_sender-title')[0].textContent;

	function loadImage(url) {
		return new Promise((resolve) => {
			let img = new Image();
			img.onload = () => resolve(img);
			img.src = url;
		});
	}

	loadImage(imgUrl).then((logo) => {
		const doc = new jsPDF();
		doc.addImage(logo, 'PNG', 0, 0, 210, 297);

		var totalWidth = doc.internal.pageSize.getWidth();

		doc.setFontSize(30);
		doc.setTextColor(211, 211, 211);
		doc.setFontType('bold');
		doc.text(receive, 105, 183, null, null, 'center');

		var splitTitle = doc.splitTextToSize(desc, 500);
		doc.setFontSize(10);
		doc.setTextColor(146, 146, 146);
		doc.text(splitTitle, 105, 240, null, null, 'center');

		doc.setFontSize(20);
		doc.setTextColor(146, 146, 146);
		var senderText = doc.getTextWidth(sender);
		doc.text(sender, 203, 285, null, null, 'right');

		doc.setFontSize(10);
		doc.setTextColor(146, 146, 146);
		var presentedBy = doc.getTextWidth(lstxt);
		var lastText = totalWidth - senderText - presentedBy - 9;
		doc.text(lstxt, lastText, 285);

		doc.save('download.pdf');
	});
}

if (window.location.pathname == '/home/') {
	var CATEGORY = false;

	var autocomplete;
	var Latlng;
	var Category;
	var ApiImage;
	var FinalData;
	var nearestPoints = [];

	fetch('https://theoneinamillionglobe.com/wp-json/image-api/image-item/').then((res) => res.text()).then((data) => {
		ApiImage = JSON.parse(data);
	});

	let Arr = [];
	let allCat;
	fetch('https://theoneinamillionglobe.com/wp-json/categories-api/categories-item/')
		.then((res) => res.text())
		.then((data) => JSON.parse(data))
		.then((data) => {
			let ArrCategory = Object.values(data.categories);
			Arr.push(`<option value='a' selected="selected">Select Category</option>`);
			for (let i = 0; i < ArrCategory.length; i++) {
				Arr.push(`<option value=${i}>${ArrCategory[i]}</option>`);
			}
			Arr.splice(2, 2);
			allCat = ArrCategory;
			return Arr;
		})
		.then((res) => {
			let Search = document.getElementById('search');
			Search.innerHTML = `
            <div>
            <select class="categories" id="categories"  onchange="getVal(this)">
        ${res}
        </select>
            </div>
            <div>
           <input id="searchTextField" type="text" placeholder="Select your location">
            </div>
               <div class="btn-wrap"> <button class="button-default button-small" onclick='searchData()'>Search</button>
            <button class="button-default button-small" onclick='resetData()'>Reset</button>
            </div>
`;
		})
		.then((data) => {
			autocomplete = new google.maps.places.Autocomplete(document.getElementById('searchTextField'), {
				types: [ 'geocode' ]
			});
			google.maps.event.addListener(autocomplete, 'place_changed', function() {
				let Place = autocomplete.getPlace().geometry.location;
				let Lat = Place.lat();
				let Long = Place.lng();
				Latlng = { lat: Lat, lng: Long };
			});
		});

	function getVal(data) {
		Category = data.options[data.selectedIndex].text;
	}

	let isSearching = false;

	function searchData() {
		let data = {};
		let loc,
			cat = false;
		let Cat = typeof Category !== 'undefined' ? { categories: Category } : '';
		let Lat = typeof Latlng !== 'undefined' ? Latlng : '';
		if (Cat) {
			isSearching = true;
			var url = new URL('https://theoneinamillionglobe.com/wp-json/search-cluster-api/search-item'),
				params = { ...Cat };
			Object.keys(params).forEach((key) => url.searchParams.append(key, params[key]));
			fetch(url).then((res) => res.text()).then((data) => {
				let Main = JSON.parse(data);

				if (Main.length == 0) {
					showData([ {} ], true);
				} else {
					showData([ Main.clusters ], true);
				}
			});
		}
		if (Latlng) {
			myGlobe.pointOfView({ lat: Latlng.lat, lng: Latlng.lng, altitude: 0.77 }, 1000);
		}
	}

	let FirstArr = [];
	var TotalData = [];

	function resetData() {
		isSearching = false;
		myGlobe.pointOfView({ lat: 0, lng: 0, altitude: 2.5 }, [ 1000 ]);
		document.getElementById('searchTextField').value = '';
		document.getElementById('categories').value = 'a';
		Category = undefined;
		Latlng = undefined;
		showData([ Clusters ]);
	}
	function toggleModal(data, time = 1000) {
		let latitude = parseFloat(data.lat),
			longitude = parseFloat(data.lng);
		let Time = time;
		let globePov = myGlobe.pointOfView();
		let povLat = globePov.lat.toFixed(5),
			povLng = globePov.lng.toFixed(5);
		if (longitude.toFixed(5) !== povLng && latitude.toFixed(5) !== longitude) {
			myGlobe.pointOfView({ lat: latitude, lng: longitude }, [ 1000 ]);
		} else {
			Time = 0;
		}
		setTimeout(() => {
			let check = document.createElement('div');
			check.className = 'modal';
			if (data.bool_hidden == 'true') {
				check.innerHTML = `
    <span class="close-overlay" onclick='closeModal()'></span>
    <div id=content' class="modal-content">
                    <span class="close-button" onclick='closeModal()'>×</span>

                    <div class="main-template">
                        <div class="content-template">
                            <span class="main-name fc-receive fc-receive-left">${data.Receiver}</span>
                            <div>
                            <img src="https://theoneinamillionglobe.com/wp-content/uploads/2020/07/logo-mid.png">
                            </div>
                            <p class="desc"> ${data.Description}
                            </p>
                            <div class="sender-info">
                                <span class="fc_sender-title">Presented by</span><span class="main-name fc-send fc-send-left">${data.Sender}</span>
                            </div>
                        </div>
                     </div>
                 </div>

  `;
			} else {
				check.innerHTML = `
    <span class="close-overlay" onclick='closeModal()'></span>
    <div id=content' class="modal-content" >
                    <span class="close-button" onclick='closeModal()'>×</span>

                    <div class="main-template">
                        <div class="content-template">
                            <span class="main-name fc-receive fc-receive-left">RESERVED</span>
                            <div>
                            <img src="https://theoneinamillionglobe.com/wp-content/uploads/2020/07/logo-mid.png">
                            </div>
                            <p class="desc"> This spot on The One In A Million Globe has been reserved for someone special,  a parent or partner who’s always there for you? A doctor or nurse who keeps you safe? Or a special friend you just couldn’t live without? Why not show your special person how much they mean to you by including them in the One in a Million globe, a unique digital showcase that spans the world.
                            </p>
                            <div class="sender-info">
                                <span class="fc_sender-title">Presented by</span><span class="main-name fc-send fc-send-left">An Admirer</span>
                            </div>
                        </div>
                     </div>
                 </div>
  `;
			}
			document.getElementById('model').appendChild(check);
			$('.modal-content').css('background-image', 'url(' + ApiImage.url + ')');
		}, Time);
	}
	function closeModal() {
		handleClick = false;
		let Modal = document.querySelector('.modal');
		Modal.parentNode.removeChild(Modal);
	}

	var Distance = null;
	var myGlobe = Globe()(document.getElementById('globeViz'))
		.globeImageUrl('https://theoneinamillionglobe.com/wp-content/uploads/2020/06/globeimg.gif')
		.backgroundImageUrl('https://theoneinamillionglobe.com/wp-content/uploads/2020/06/blackbg.png')
		.bumpImageUrl('//cdn.jsdelivr.net/npm/three-globe/example/img/earth-topology.png')
		.showGraticules([ true ])
		.pointColor(() => 'red')
		.pointAltitude(0.01)
		.pointRadius(0.4)
		.labelColor('red')
		.onZoom(function(currentPoint) {
			globeZoom = currentPoint.altitude;
		})
		.onPointHover((point) => {
			if (point !== null && handleClick == false) {
				handleClick = true;
				if (!point.hasOwnProperty('points')) {
					toggleModal(point);
				} else {
					loadGoogleMaps(point);
					myGlobe.pointOfView({ lat: point.lat, lng: point.lng, altitude: 0.1 }, [ 3000 ]);
					setTimeout(function() {
						document.getElementById('search').style.display = 'none';
						jQuery('#simple-map').show();
					}, 3000);

					point.points.map((item) => {
						marker = new google.maps.Marker({
							position: new google.maps.LatLng(Number(item.lat), Number(item.lng)),
							map: mapObject,
							visible: true,
							animation: google.maps.Animation.DROP
						});

						google.maps.event.addListener(
							marker,
							'click',
							(function(marker, i) {
								return function() {
									toggleModal(item, 100);
								};
							})(marker, i)
						);

						markers.push(marker);
					});

					google.maps.event.addListener(mapObject, 'zoom_changed', function() {
						zoomLevel = mapObject.getZoom();
						if (zoomLevel <= 10) {
							$('#simple-map').hide();
							document.getElementById('search').style.display = 'flex';
							myGlobe.pointOfView({ lat: 0, lng: 0, altitude: 2.5 }, 100);
							handleClick = false;
							//Remove Markers
							for (var i = 0; i < markers.length; i++) {
								markers[i].setMap(null);
							}
							markers = [];
						}
					});
					myGlobe.pointOfView({ lat: point.lat, lng: point.lng, altitude: 0.88 }, 100);
				}
			}
		});
	let AllPoint;
	let WholeData;
	let Clusters;
	let TestPoints;
	// jQuery('.mloader').show();
	var getData = (CATEGORY) =>
		Promise.all([
			fetch('https://theoneinamillionglobe.com/wp-json/map-api/distance-item').then((res) => res.text())
		]).then(([ data ]) => {
			let Check = JSON.parse([ data ]);
			let resArr = [];
			Clusters = Check.clusters;
			AllPoint = Check.points;
			showData([ Check.clusters ]);
			TestPoints = Object.values(Check.points);

			setTimeout(function() {
				jQuery('.mloader').hide();
			}, 2000);
		});

	let showData = (data) => {
		myGlobe.pointsData(Object.values(data[0])).controls().minDistance = 110;
	};

	getData(CATEGORY);
}

function loadGoogleMaps(point) {
	let mapElement = document.getElementById('simple-map');

	if (!mapObject) {
		mapObject = new google.maps.Map(mapElement, {
			zoom: 12,
			center: {
				lat: Number(point.lat),
				lng: Number(point.lng)
			},
			styles: [
				{ elementType: 'geometry', stylers: [ { color: '#242f3e' } ] },
				{ elementType: 'labels.text.stroke', stylers: [ { color: '#242f3e' } ] },
				{ elementType: 'labels.text.fill', stylers: [ { color: '#746855' } ] },
				{
					featureType: 'administrative.locality',
					elementType: 'labels.text.fill',
					stylers: [ { color: '#d59563' } ]
				},
				{
					featureType: 'poi',
					elementType: 'labels.text.fill',
					stylers: [ { color: '#d59563' } ]
				},
				{
					featureType: 'poi.park',
					elementType: 'geometry',
					stylers: [ { color: '#263c3f' } ]
				},
				{
					featureType: 'poi.park',
					elementType: 'labels.text.fill',
					stylers: [ { color: '#6b9a76' } ]
				},
				{
					featureType: 'road',
					elementType: 'geometry',
					stylers: [ { color: '#38414e' } ]
				},
				{
					featureType: 'road',
					elementType: 'geometry.stroke',
					stylers: [ { color: '#212a37' } ]
				},
				{
					featureType: 'road',
					elementType: 'labels.text.fill',
					stylers: [ { color: '#9ca5b3' } ]
				},
				{
					featureType: 'road.highway',
					elementType: 'geometry',
					stylers: [ { color: '#746855' } ]
				},
				{
					featureType: 'road.highway',
					elementType: 'geometry.stroke',
					stylers: [ { color: '#1f2835' } ]
				},
				{
					featureType: 'road.highway',
					elementType: 'labels.text.fill',
					stylers: [ { color: '#f3d19c' } ]
				},
				{
					featureType: 'transit',
					elementType: 'geometry',
					stylers: [ { color: '#2f3948' } ]
				},
				{
					featureType: 'transit.station',
					elementType: 'labels.text.fill',
					stylers: [ { color: '#d59563' } ]
				},
				{
					featureType: 'water',
					elementType: 'geometry',
					stylers: [ { color: '#17263c' } ]
				},
				{
					featureType: 'water',
					elementType: 'labels.text.fill',
					stylers: [ { color: '#515c6d' } ]
				},
				{
					featureType: 'water',
					elementType: 'labels.text.stroke',
					stylers: [ { color: '#17263c' } ]
				}
			]
		});

		infowindow = new google.maps.InfoWindow();
	} else {
		mapObject.setCenter({
			lat: Number(point.lat),
			lng: Number(point.lng)
		});
		mapObject.setZoom(13);

		setTimeout(function() {
			google.maps.event.trigger(mapObject, 'resize');
			mapObject.setZoom(12);
		}, 3000);
	}
}