const Ziggy = {"url":"http:\/\/localhost:8000","port":8000,"defaults":{},"routes":{"homepage":{"uri":"\/","methods":["GET","HEAD"]},"contact":{"uri":"contact","methods":["GET","HEAD"]},"test.show":{"uri":"test","methods":["GET","HEAD"]},"user.login":{"uri":"login","methods":["GET","HEAD"]},"signup":{"uri":"signup","methods":["GET","HEAD"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
