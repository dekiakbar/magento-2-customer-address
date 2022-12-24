var config = {
    map: {
        '*': {
            'select2': 'Deki_CustomerAddress/js/select2.min',
        }
    },
    shim: {
        'select2': {
            deps: ['Deki_CustomerAddress/js/select2.min'],
            exports: 'jquery'
        }
    }
};