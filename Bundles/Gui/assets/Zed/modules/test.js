module.exports = {
    run: function() {
        console.warn('this is the test module into ZED GUI bundle');

        setTimeout(function(){
        	alert('Test performed: everything is fine, buddy!');
        }, 2000);
    }
}
