/*
 * @package WordPress
 * @subpackage Formidable, gfirem
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */
var wrappers = document.getElementsByClassName("gfirem-signature-pad");

[].forEach.call(wrappers, process_fields);

function process_fields(item, index) {
	var field_container = item.querySelector("[data-action=store-sign]"),
		clearButton = item.querySelector("[data-action=clear]"),
		canvas = item.querySelector("canvas"),
		data = field_container.getAttribute('value'),
		id = field_container.getAttribute('id'),
		signaturePad;

	// Adjust canvas coordinate space taking into account pixel ratio,
	// to make it look crisp on mobile devices.
	// This also causes canvas to be cleared.
	function resizeCanvas() {
		// When zoomed out to less than 100%, for some very strange reason,
		// some browsers report devicePixelRatio as less than 1
		// and only part of the canvas is cleared then.
		var ratio = Math.max(window.devicePixelRatio || 1, 1);
		canvas.width = canvas.offsetWidth * ratio;
		canvas.height = canvas.offsetHeight * ratio;
		canvas.getContext("2d").scale(ratio, ratio);
	}

	window.onresize = resizeCanvas;
	resizeCanvas();

	signaturePad = new SignaturePad(canvas, {
		onEnd: function (event) {
			var dotCollection = {};
			var merge = {};

			merge['uri'] = signaturePad.toDataURL("image/jpeg");
			var dataCollection = signaturePad.toData();
			merge['point'] = dataCollection;
			if (data) {
				dotCollection = JSON.parse(data);
				merge['point'] = dataCollection.concat(dotCollection.point);
				merge['id'] = dotCollection.id;
			}
			else {
				merge['point'] = dataCollection;
			}
			field_container.setAttribute('value', JSON.stringify(merge));
		},
		backgroundColor: gfirem_signature.config[id].backgroundcolor,
		penColor: gfirem_signature.config[id].pencolor,
		width: gfirem_signature.config[id].width,
		height: gfirem_signature.config[id].height
	});

	if (data) {
		var pointData = JSON.parse(data);
		console.log(data);
		signaturePad.fromData(pointData.point);
	}

	clearButton.addEventListener("click", function (event) {
		signaturePad.clear();
		event.preventDefault();
		return false;
	});
}

