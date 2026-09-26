import { chromium } from 'playwright';

const shotDir = 'C:/Users/daniel/AppData/Local/Temp/claude/C--wamp64-www-newGraduateSchoolPortal/ae3998dd-9b09-484a-87da-c020affa1be5/scratchpad';

const browser = await chromium.launch();
const page = await browser.newPage();
page.on('console', msg => { if (msg.type() === 'error') console.log('CONSOLE ERROR:', msg.text()); });
page.on('pageerror', err => console.log('PAGE ERROR:', err.message));

try {
  await page.goto('http://127.0.0.1:8000/login');
  await page.fill('input[name="email"], input[type="email"]', 'danielidonije@gmail.com');
  await page.fill('input[name="password"], input[type="password"]', 'TempTest123!');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');
  console.log('After login URL:', page.url());

  await page.goto('http://127.0.0.1:8000/registered-courses');
  console.log('Index URL:', page.url());
  await page.screenshot({ path: `${shotDir}/1-index.png`, fullPage: true });

  await page.selectOption('#faculty_id', '13');
  await page.waitForTimeout(500);
  await page.selectOption('#department_id', '40');
  await page.selectOption('#programme_id', '1');
  await page.selectOption('#semester', '1');
  await page.screenshot({ path: `${shotDir}/2-filled-filter.png`, fullPage: true });

  await Promise.all([
    page.waitForNavigation(),
    page.click('button:has-text("Generate Courses")'),
  ]);
  console.log('After filter URL:', page.url());
  await page.screenshot({ path: `${shotDir}/3-results.png`, fullPage: true });

  const rowCount = await page.locator('input[name="course_ids[]"]').count();
  console.log('Course checkbox rows found:', rowCount);

  // check the "select all" header checkbox
  await page.locator('thead input[type="checkbox"]').check();
  const checkedCount = await page.locator('input[name="course_ids[]"]:checked').count();
  console.log('Checked after select-all:', checkedCount);
  await page.screenshot({ path: `${shotDir}/4-select-all.png`, fullPage: true });

  // uncheck select-all, then check just the first row manually
  await page.locator('thead input[type="checkbox"]').uncheck();
  await page.locator('input[name="course_ids[]"]').first().check();
  const singleChecked = await page.locator('input[name="course_ids[]"]:checked').count();
  console.log('Checked after single select:', singleChecked);

  await page.selectOption('#programme_of_study_id', { index: 1 });
  await page.selectOption('#level_id', { index: 1 });
  await page.selectOption('#session', { index: 1 });
  await page.selectOption('#status', 'active');
  await page.screenshot({ path: `${shotDir}/5-details-filled.png`, fullPage: true });

  await Promise.all([
    page.waitForNavigation(),
    page.click('button:has-text("Register Selected Courses")'),
  ]);
  console.log('After submit URL:', page.url());
  await page.screenshot({ path: `${shotDir}/6-after-submit.png`, fullPage: true });

  const bodyText = await page.locator('body').innerText();
  console.log('--- Flash check ---');
  console.log(bodyText.includes('registered successfully') ? 'SUCCESS message found' : 'NO success message found');

} catch (e) {
  console.log('TEST FAILED:', e.message);
  await page.screenshot({ path: `${shotDir}/error.png`, fullPage: true }).catch(() => {});
} finally {
  await browser.close();
}
