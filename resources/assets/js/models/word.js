import Definition from './definition';

export default class Word {
    constructor(data) {
        for(let field in data) {
            if(field == 'word') {
                this[field] = data[field];
            }
            if(field == 'pronunciation') {
                this[field] = data[field]['all'];
            }
            if(field == 'results') {
                this.definitions = [];
                for(let i=0 ; i < data[field].length ; i++) {
                    this.definitions.push(new Definition(data[field][i]));
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
};