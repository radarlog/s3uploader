import $ from 'jquery';
import fetch from 'node-fetch';

describe('Doop Test Suite', () => {
    const url = process.env.TRAVIS_APP_HOST as string;

    async function loadHtml() {
        const response = await fetch(url);

        return response.text();
    }

    test('Upload file input has been rendered', async () => {
        document.documentElement.innerHTML = await loadHtml();

        expect($('#upload_image').hasClass('custom-file-input')).toBeTruthy();
    });
});
