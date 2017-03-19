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
		backgroundColor: gfirem_signature.config[id].background,
		penColor: gfirem_signature.config[id].pencolor,
		width: gfirem_signature.config[id].width,
		height: gfirem_signature.config[id].height
	});

	if (data) {
		var pointData = JSON.parse(data);
		console.log(pointData);
		signaturePad.fromData(pointData.point);
	}

	clearButton.addEventListener("click", function (event) {
		signaturePad.clear();
		event.preventDefault();
		return false;
	});
}

