// import {Fancybox as NativeFancybox} from "@fancyapps/ui/dist/fancybox.esm.js";
import {Fancybox} from "@fancyapps/ui/src/Fancybox/Fancybox.js";
import "@fancyapps/ui/dist/fancybox.css";

const options = {
    closeExisting: true,
    mainClass: 'Test Test Test Test Test Test ',
    closeButton: 'outside'
}

function fancyBox(props) {
    const delegate = document.querySelectorAll('[data-fancybox]');
    const opts = options || {};

    // return NativeFancybox.bind(delegate, opts);
    return new Fancybox(delegate, opts);
}

export default fancyBox;
