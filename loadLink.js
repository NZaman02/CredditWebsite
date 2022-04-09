const linkPreviewGenerator = require("link-preview-generator");

var link = "https://www.bbc.co.uk/sport/winter-olympics/60450423";

const previewData = await linkPreviewGenerator(
    link
  );

console.log(previewData);
  /*
  {
    title: 'Kiteboarding: Stylish Backroll in 4 Sessions - Ride with Blake: Vlog 20',
    description: 'The backroll is a staple in your kiteboarding trick ' +
      'bag. With a few small adjustments, you can really ' +
      'improve your style and make this basic your own. ' +
      'Sessio...',
    domain: 'youtube.com',
    img: 'https://i.ytimg.com/vi/8mqqY2Ji7_g/hqdefault.jpg'
  }
  */