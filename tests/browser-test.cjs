const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');
const assert = require('node:assert/strict');
(async()=>{
const browser=await chromium.launch({headless:true,channel:"chrome"});
for (const width of [1440,768,390,320]) {
const page=await browser.newPage({viewport:{width,height:1000}});
await page.route(/fonts.googleapis|unpkg.com/,r=>r.abort());
await page.goto('http://127.0.0.1:8765/.local-preview/index.html');
assert.equal(await page.locator('main').count(),1);
assert.equal(await page.locator('h1').count(),1);
const overflow=await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth);
assert.equal(overflow,false,'Overflow at '+width);
if(width<=768){assert(await page.locator('.nav-contacto').isVisible());assert(await page.locator('.nav-proyectos').isVisible());}
await page.locator('.case-details').first().locator('summary').click();
assert(await page.locator('.case-details').first().locator('h4').first().isVisible());
await page.locator('#btn-reject-cookies').click();
await page.locator('#cookie-settings').click();
assert(await page.locator('#cookie-banner').isVisible());
await page.locator('#btn-accept-cookies').click();
await page.evaluate(() => { document.activeElement.blur(); window.scrollTo(0,0); });
await page.screenshot({path:'.local-preview/home-'+width+'.png',fullPage:true});
await page.close();
}
const page=await browser.newPage({viewport:{width:390,height:900}});
await page.route(/fonts.googleapis|unpkg.com/,r=>r.abort());
await page.goto('http://127.0.0.1:8765/.local-preview/contact.html?servicio=wordpress');
assert.equal(await page.locator('#service').inputValue(),'Desarrollo web');
await page.locator('#btn-reject-cookies').click();
await page.locator('#name').fill('QA'); await page.locator('#email').fill('qa@example.com'); await page.locator('#message').fill('Prueba de solicitud');await page.locator('[name="privacy"]').check();
await page.route('**/contact-fixture',r=>r.fulfill({contentType:'application/json',body:JSON.stringify({success:false,data:'Error de validación'})}));
await page.locator('button[type="submit"]').click(); await page.waitForFunction(()=>document.querySelector('#form-response').dataset.state==='error');
assert.equal(await page.locator('#name').inputValue(),'QA');
await page.unroute('**/contact-fixture');
await page.route('**/contact-fixture',r=>r.fulfill({contentType:'application/json',body:JSON.stringify({success:true,data:'Solicitud registrada'})}));
await page.locator('button[type="submit"]').click(); await page.waitForFunction(()=>document.querySelector('#form-response').dataset.state==='success');
assert.equal(await page.locator('#name').inputValue(),'');
await page.close();
const nojs=await browser.newContext({javaScriptEnabled:false,viewport:{width:390,height:900}});
const plain=await nojs.newPage();await plain.goto('http://127.0.0.1:8765/.local-preview/index.html');
assert(await plain.locator('#contacto').isVisible());assert(await plain.locator('.nav-contacto').isVisible());
assert.equal(await plain.locator('form').getAttribute('method'),'post');
await browser.close(); console.log('PASS: responsive 320/390/768/1440, mobile contact/projects, case details, consent, form error/success and no-JS.');
})().catch(e=>{console.error(e);process.exit(1)});


