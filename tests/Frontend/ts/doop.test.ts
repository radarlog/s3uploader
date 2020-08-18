import $ from 'jquery';
import fetch from 'node-fetch';

describe('Doop Test Suite', () => {
    const url = 'http://0.0.0.0';

    async function loadHtml() {
        const response = await fetch(url);

        return response.text();
    }

    test('Upload file input has been rendered', async () => {
        document.documentElement.innerHTML = await loadHtml();

        expect($('#upload_image').hasClass('custom-file-input')).toBeTruthy();
    });
});
