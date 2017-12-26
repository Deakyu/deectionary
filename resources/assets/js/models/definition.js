export default class Definition {
    constructor(data) {
        for(let field in data) {
            if(field == 'definition') {
                this[field] = data[field];
            }
            if(field == 'partOfSpeech') {
                delete this[field];
                this.form = data[field];
            }
            if(field == 'examples') {
                if(data[field][0]) {
                    this.example = data[field][0];
                } else {
                    this.example = null;
                }
            }
        }
    }
    get(field) {
        if(this[field]) {
            return this[field];
        }
        return false;
    }
    hasExample() {
        return this.hasOwnProperty('example');
    }
};