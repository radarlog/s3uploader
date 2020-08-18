import $ from 'jquery';
import fetch from 'node-fetch';

describe('Doop Test Suite', () => {
    const url = 'http://' + process.env.TRAVIS_APP_HOST;

    async function loadHtml() {
        const response = await fetch(url);

        console.log(url);

        return response.text();
    }

    test('Upload file input has been rendered', async () => {
        const html = await loadHtml()

        console.log(html);

        document.documentElement.innerHTML = html;

        expect($('#upload_image').hasClass('custom-file-input')).toBeTruthy();
    });
});
