function clickOutside(element, callback) {
    document.addEventListener("click", (evt) => {
        const flyoutElement = element;
        let targetElement = evt.target; // clicked element

        do {
            if (targetElement == flyoutElement) {
                // Do nothing, just return.
                callback(true);
                return;
            }
            // Go up the DOM.
            targetElement = targetElement.parentNode;
        } while (targetElement);

        // Do something useful here.
        callback(false);
    });
}

export default clickOutside;