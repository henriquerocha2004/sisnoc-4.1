var fs = require('fs');
var path = require('path');
var server = require('http').createServer(onRequest);
var io = require('socket.io')(server);
var SSHClient = require('ssh2').Client;
var host = null;
var mainUserName = "henrique";
var mainPassword = "Casa1803";
var altenateUserName = "teste";
var alternatePass = "teste";

// Load static files into memory
var staticFiles = {};
var basePath = path.join(require.resolve('xterm'), '..');

[
  'addons/fit/fit.js',  
  'src/xterm.css',
  'xterm.js'
].forEach(function(f) {
  staticFiles['/' + f] = fs.readFileSync(path.join(basePath, f));
  
});
staticFiles['/terminal'] = fs.readFileSync('index.html');

//console.log(staticFiles['/term']);

// Handle static file serving
function onRequest(req, res) {
  var file;

    var urlString = req.url;
    var url = urlString.split("?");
    
    if(/terminal/.test(url[0])){
      var param = url[1].split("=");
      var ip = param[1].split("&");
      var regIp = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
      if(param[0] == 'ip' && regIp.test(ip[0])){
         host = ip[0];
         console.log(param[2]);
         if(param[2] == 'a'){
           mainUserName = altenateUserName;
           mainPassword = alternatePass;
         }else{
           mainUserName = 'henrique';
           mainPassword = 'Casa1803';
         } 

      }else{
        console.log("Invalid IP");
        res.writeHead(404);
        res.end();
      }

    }

    if (req.method === 'GET' && (file = staticFiles[url[0]])) {
      res.writeHead(200, {
        'Content-Type': 'text/'
                        + (/css$/.test(url[0])
                           ? 'css'
                           : (/js$/.test(url[0]) ? 'javascript' : 'html'))
      });
      return res.end(file);
    }
    res.writeHead(404);
    res.end();
  //}

}

io.on('connection', function(socket) {
  var conn = new SSHClient();
  conn.on('ready', function() {
    socket.emit('data', '\r\n*** Conexão Feita via SSH ***\r\n');
    conn.shell(function(err, stream) {
      if (err)
        return socket.emit('data', '\r\n*** Erro no Shell : ' + err.message + ' ***\r\n');
      socket.on('data', function(data) {
        stream.write(data);
      });
      stream.on('data', function(d) {
        socket.emit('data', d.toString('binary'));
      }).on('close', function() {
        conn.end();
      });
    });
  }).on('close', function() {
    socket.emit('data', '\r\n*** Conexão SSH Fechada ***\r\n');
  }).on('error', function(err) {
    socket.emit('data', '\r\n*** Erro de Conexão SSH : ' + err.message + ' ***\r\n');    
  }).connect({
    host: host,
    username: mainUserName,
    password: mainPassword
  });
});

server.listen(8000, function(){
  console.log("ok");
});