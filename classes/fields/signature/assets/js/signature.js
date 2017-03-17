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
			if (!gfirem_signature.is_front) {
				field_container.setAttribute('value', JSON.stringify(signaturePad.toData()));
			}
		},
		backgroundColor:gfirem_signature.config[id].background,
		penColor:gfirem_signature.config[id].pencolor
	});

	if (data) {
		signaturePad.fromData(JSON.parse(data));
	}

	if (gfirem_signature.is_front) {
		signaturePad.off();
	}
	else {
		clearButton.addEventListener("click", function (event) {
			signaturePad.clear();
			event.preventDefault();
			return false;
		});
	}
}

