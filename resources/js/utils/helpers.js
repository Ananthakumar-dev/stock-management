export function decodeHtmlEntities(str) {
    const parser = new DOMParser();
    const decodedString = parser.parseFromString(`<!doctype html><body>${str}`, 'text/html').body.textContent;
    return decodedString;
}
