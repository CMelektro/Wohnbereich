/* One coordinate system for the actual panoramic image. No third-room collage. */
function createPanoramaRenderer(host){
 const NS='http://www.w3.org/2000/svg';
 function s(tag,a,p){const n=document.createElementNS(NS,tag);for(const[k,v]of Object.entries(a||{}))n.setAttribute(k,v);if(p)p.append(n);return n}
 const root=s('svg',{viewBox:'0 0 1942 809','aria-hidden':'true',class:'scene-svg'},host);
 const defs=s('defs',{},root);
 function gradient(id,stops,attr={}){const g=s('linearGradient',{id,...attr},defs);for(const[o,c,alpha]of stops)s('stop',{offset:o,'stop-color':c,'stop-opacity':alpha},g)}
 gradient('metal',[['0%','#161818',1],['42%','#53534d',1],['70%','#262a2a',1],['100%','#111414',1]]);
 gradient('beam',[['0%','#ffdda0',.34],['100%','#f8d091',0]],{x1:0,y1:0,x2:0,y2:1});
 const radial=s('radialGradient',{id:'pool'},defs);s('stop',{offset:0,'stop-color':'#ffdfaa','stop-opacity':.28},radial);s('stop',{offset:1,'stop-color':'#ffdfaa','stop-opacity':0},radial);
 const filter=s('filter',{id:'soft',x:'-80%',y:'-80%',width:'260%',height:'260%'},defs);s('feGaussianBlur',{stdDeviation:10},filter);
 const slats=s('pattern',{id:'slats',width:12,height:12,patternUnits:'userSpaceOnUse'},defs);s('rect',{width:12,height:12,fill:'#353731'},slats);s('path',{d:'M0 2H12',stroke:'#7b7b6e','stroke-width':1.4},slats);s('path',{d:'M0 10H12',stroke:'#141817','stroke-width':2},slats);
 const windows={Kitchen:[[[0,91],[82,115],[82,585],[0,608]],[[103,121],[179,142],[178,567],[102,582]]],Dining:[[[764,188],[966,186],[966,435],[764,460]],[[987,187],[1188,185],[1188,442],[987,441]]],Living:[[[1281,186],[1478,185],[1478,411],[1372,396],[1318,409],[1281,429]],[[1500,185],[1648,184],[1648,502],[1582,464],[1500,462]]]};
 const blinds=s('g',{},root),lights=s('g',{},root),sockets=s('g',{},root);
 const fixturePresets={
 Kitchen:{pendant:[470,225,1],spots:[465,93,1],led:[440,330,1],floor:[204,360,.85],panel:[470,100,1],wall:[710,277,.8],path:[204,517,.8]},
 Dining:{pendant:[975,258,1.1],spots:[975,112,1.1],led:[975,179,1.05],floor:[720,310,1],panel:[975,110,1],wall:[1241,280,.8],path:[1212,499,.8]},
 Living:{pendant:[1470,266,1],spots:[1470,100,1.15],led:[1810,570,.75],floor:[1220,300,1],panel:[1470,105,1],wall:[1723,271,.8],path:[1737,533,.8]}
 };
 function drawBlind(a,d,i){
  const points=windows[a.id][i];if(!points)return;const id=d.ident+'Clip',clip=s('clipPath',{id},defs);s('polygon',{points:points.map(p=>p.join(',')).join(' ')},clip);
  const xs=points.map(p=>p[0]),ys=points.map(p=>p[1]),x=Math.min(...xs),y=Math.min(...ys),w=Math.max(...xs)-x,h=Math.max(...ys)-y;
  const known=Number.isFinite(d.position),g=s('g',{'data-device':d.ident,'clip-path':'url(#'+id+')'},blinds);
  const mask=s('mask',{id:d.ident+'Mask',maskUnits:'userSpaceOnUse',x,y,width:w,height:h},defs);s('rect',{x,y,width:w,height:h,fill:'white'},mask);
  if(a.id==='Dining'){s('ellipse',{cx:745,cy:348,rx:57,ry:81,fill:'black'},mask);s('rect',{x:717,y:421,width:64,height:65,rx:15,fill:'black'},mask);s('ellipse',{cx:975,cy:397,rx:50,ry:49,fill:'black'},mask);for(const cx of [906,1012,1117])s('ellipse',{cx,cy:440,rx:43,ry:22,fill:'black'},mask)}
  s('rect',{x,y,width:w,height:h*(known?Math.max(0,Math.min(100,d.position))/100:1),fill:'url(#slats)',opacity:known?.93:.1,mask:'url(#'+d.ident+'Mask)'},g);
 }
 function pose(a,d){if(d.autoPosition===false)return [d.x*1942/3000+({Kitchen:0,Dining:647,Living:1295}[a.id]),d.y*809/650,d.scale/100];const p=[...(fixturePresets[a.id][d.type]||fixturePresets[a.id].floor)],peers=a.lights.filter(t=>t.enabled&&t.type===d.type&&t.autoPosition!==false);if(peers.length>1){const n=peers.findIndex(t=>t.ident===d.ident);if(d.type==='led'){p[0]+=(n-(peers.length-1)/2)*220/peers.length;p[2]/=peers.length}else{p[0]+=(n-(peers.length-1)/2)*125;p[2]*=.72}}return p}
 function drawLight(a,d){
  const [x,y,k]=pose(a,d),g=s('g',{transform:`translate(${x} ${y}) scale(${k})`,'data-device':d.ident,'data-type':d.type},lights),on=d.on===true,level=on?(d.useDim?(Number.isFinite(d.level)?d.level/100:.45):1):0;
  const glow=s('g',{opacity:level,'data-glow':d.ident},g),metal=s('g',{fill:'url(#metal)',stroke:'#66665d','stroke-width':1},g),source=s('g',{fill:on?'#ffe1a5':'#727267',stroke:on?'#ffe1a5':'#727267'},g);
  const cone=(cx,sy,width,end)=>s('path',{d:`M${cx-8} ${sy}L${cx-width} ${end}Q${cx} ${end+35} ${cx+width} ${end}L${cx+8} ${sy}Z`,fill:'url(#beam)',filter:'url(#soft)'},glow);
  switch(d.type){
   case 'pendant':for(const dx of [-65,0,65]){s('path',{d:`M${dx} ${-y/k+30}V-7`,fill:'none',stroke:'#343737','stroke-width':2},metal);s('path',{d:`M${dx-29} 14Q${dx-27} -13 ${dx} -15Q${dx+27} -13 ${dx+29} 14Z`},metal);s('ellipse',{cx:dx,cy:14,rx:26,ry:3},source);cone(dx,17,55,a.id==='Kitchen'?220:170)}break;
   case 'spots':for(const dx of [-88,0,88]){s('ellipse',{cx:dx,cy:0,rx:15,ry:4},metal);s('ellipse',{cx:dx,cy:1,rx:9,ry:2},source);cone(dx,4,65,a.id==='Kitchen'?355:340)}break;
   case 'led':{const width=a.id==='Kitchen'?235:a.id==='Dining'?220:120;s('rect',{x:-width,y:-2,width:width*2,height:5,rx:2},metal);s('path',{d:`M${-width} 2H${width}`,'stroke-width':3},source);s('rect',{x:-width,y:4,width:width*2,height:a.id==='Living'?48:80,fill:'url(#beam)',filter:'url(#soft)'},glow);break;}
   case 'panel':s('path',{d:'M-72-9H60L78 8H-54Z'},metal);s('path',{d:'M-66-6H58L69 5H-54Z','fill-opacity':on?.8:.2},source);cone(0,10,125,325);break;
   case 'wall':s('rect',{x:-16,y:-24,width:32,height:44,rx:3},metal);s('path',{d:'M-14 19H14M-14-23H14','stroke-width':2},source);cone(0,22,65,165);s('path',{d:'M-12-25L-55-135H55L12-25Z',fill:'url(#pool)',filter:'url(#soft)'},glow);break;
   case 'path':s('rect',{x:-16,y:-11,width:32,height:24,rx:3},metal);s('path',{d:'M-11 6H11','stroke-width':3},source);cone(0,9,48,100);break;
   default:s('path',{d:'M0 0V330M-30 330Q0 321 30 330',fill:'none','stroke-width':3},metal);s('path',{d:'M-24-20H24L40 21H-40Z'},metal);s('ellipse',{cx:0,cy:21,rx:36,ry:4},source);cone(0,25,80,320);s('ellipse',{cx:0,cy:332,rx:90,ry:25,fill:'url(#pool)',filter:'url(#soft)'},glow);
  }
 }
 function drawSocket(a,d,i){const x={Kitchen:628,Dining:1220,Living:1710}[a.id]+i*29,y={Kitchen:367,Dining:350,Living:435}[a.id],g=s('g',{transform:`translate(${x} ${y})`,'data-device':d.ident},sockets);if(d.on===true)s('rect',{x:-7,y:-7,width:36,height:36,rx:10,fill:'#ffd891',opacity:.48,filter:'url(#soft)'},g);s('rect',{width:23,height:23,rx:4,fill:d.on===true?'#f3cb90':'#303332',stroke:d.on===true?'#ffe8be':'#7b7b71','stroke-width':1.5},g);s('circle',{cx:11.5,cy:11.5,r:7,fill:'none',stroke:d.on===true?'#665335':'#aaa697'},g);s('path',{d:'M8 10v3M15 10v3',stroke:d.on===true?'#55452b':'#aaa697','stroke-width':2},g)}
 return {update(areas){for(const layer of [blinds,lights,sockets])layer.replaceChildren();for(const n of [...defs.querySelectorAll('clipPath,mask')])n.remove();for(const a of areas.filter(a=>a.enabled)){a.blinds.forEach((d,i)=>{if(d.enabled)drawBlind(a,d,i)});a.lights.forEach(d=>{if(d.enabled&&(d.useSwitch||d.useDim))drawLight(a,d)});a.sockets.forEach((d,i)=>{if(d.enabled)drawSocket(a,d,i)})}},root};
}
