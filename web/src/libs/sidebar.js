
export default [{
  icon: 'dashboard',
  title: 'Dashboard',
  href: '/'
}, {
  icon: 'layers',
  title: 'Application',
  subs: [{
    title: 'Information',
    href: '/app/info'
  }, {
    title: 'Configuration',
    href: '/app/config'
  }, {
    title: 'Registered Events',
    href: '/app/events'
  }]
}, {
  icon: 'language',
  title: 'Server',
  subs: [{
    title: 'Information',
    href: '/server/info'
  }, {
    title: 'Server Config',
    href: '/server/config'
  }, {
    title: 'Server Events',
    href: '/server/events'
  }, {
    title: 'Server Stats',
    href: '/server/stats'
  }]
}, {
  icon: 'reorder',
  title: 'Routes',
  subs: [{
    title: 'HTTP Routes',
    href: '/http/routes'
  }, {
    title: 'WebSocket Routes',
    href: '/ws/routes'
  }, {
    title: 'RPC Routes',
    href: '/rpc/routes'
  }]
}, {
  icon: 'insert_drive_file',
  title: 'Logs',
  subs: [{
    title: 'Application Log',
    href: '/app/logs'
  }, {
    title: 'Swoole Log',
    href: '/swoole/logs'
  }]
}, {
  icon: 'build',
  title: 'Tools',
  subs: [{
    // icon: 'code',
    title: 'Code Generator',
    href: '/code/gen'
  }, {
    title: 'WebSocket Test',
    href: '/ws/test'
  }, {
    title: 'Run Trace',
    href: '/run/trace'
  }]
}, {
  icon: 'info',
  title: 'About',
  href: '/about'
}]
