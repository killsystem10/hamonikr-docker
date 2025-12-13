const {ipcRenderer} = require('electron');
const {BrowserWindow} = require('electron')
const path = require('path');

ipcRenderer.send('loadData', () => {
        console.log("Event fileData sent.");
});

ipcRenderer.on('fileData', (event, connTime, connUserNm, whoami) => {
        console.log("11-=====> "+ connTime );
        console.log("22-=====> "+ connUserNm );
        console.log("33-=====> "+ whoami );
	
        $("#connUserNm").text(connUserNm);
        $("#connTime").text(connTime);
        countdown('countdown', 600);

});

