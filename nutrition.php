<!-- nutrition.php -->
<section class="nutrition-table">
  <h2>Daily Nutritional Requirements <span style="color:var(--accent)">(Bangladeshi Context)</span></h2>
  <div class="nutrition-card">
    <table>
      <thead>
        <tr>
          <th>Nutrient</th>
          <th>Primary School Students (6–11)</th>
          <th>High School Students (12–17)</th>
          <th>University Students (18–25)</th>
          <th>Adult Men (26–50)</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>Calories</td><td>1,400–1,800 kcal</td><td>1,800–2,200 kcal</td><td>2,200–2,500 kcal</td><td>2,400–2,800 kcal</td></tr>
        <tr><td>Protein</td><td>25–35 g</td><td>40–55 g</td><td>55–70 g</td><td>60–75 g</td></tr>
        <tr><td>Carbohydrates</td><td>180–220 g</td><td>250–300 g</td><td>300–350 g</td><td>320–400 g</td></tr>
        <tr><td>Fats</td><td>35–45 g</td><td>45–60 g</td><td>50–70 g</td><td>60–80 g</td></tr>
        <tr><td>Fiber</td><td>12–18 g</td><td>20–25 g</td><td>25–30 g</td><td>25–35 g</td></tr>
        <tr><td>Calcium</td><td>600–800 mg</td><td>800–1,000 mg</td><td>1,000 mg</td><td>1,000 mg</td></tr>
        <tr><td>Iron</td><td>8–12 mg</td><td>12–15 mg</td><td>17–20 mg</td><td>17 mg</td></tr>
        <tr><td>Vitamin A</td><td>400–500 mcg</td><td>600–700 mcg</td><td>700 mcg</td><td>900 mcg</td></tr>
        <tr><td>Vitamin C</td><td>35–45 mg</td><td>50–60 mg</td><td>65–75 mg</td><td>75–90 mg</td></tr>
        <tr><td>Water</td><td>1.2–1.5 liters/day</td><td>1.5–2 liters/day</td><td>2–2.5 liters/day</td><td>2.5–3 liters/day</td></tr>
      </tbody>
    </table>
    <p class="nutrition-note"><strong>Nutrition Priority List:</strong> Meals should emphasize protein and fiber for satiety, moderate fats, and steady energy from complex carbohydrates. Hydration and fresh vegetables are recommended daily.</p>
  </div>
</section>

<style>
  :root{
    --bg:#0f172a;
    --card:#0b1227;
    --muted:#94a3b8;
    --text:#000000ff;
    --primary:#22c55e;
    --accent:#38bdf8;
    --border:#1e293b;
    --ring:#86efac;
  }

  .nutrition-table{
    max-width: 1100px;
    margin: 60px auto;
    padding: 0 20px;
    color: var(--text);
  }

  .nutrition-table h2{
    text-align: center;
    font-size: clamp(22px, 2vw, 28px);
    margin-bottom: 20px;
  }

  .nutrition-card{
    background: linear-gradient(180deg, rgba(253, 253, 253, 0.9), rgba(255, 255, 255, 0.9));
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 20px;
    backdrop-filter: blur(8px);
    box-shadow: 0 8px 24px rgba(0,0,0,.4);
  }

  .nutrition-card table{
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
    border-radius: 12px;
    background: #ffffffff;
  }

  .nutrition-card thead{
    background: linear-gradient(135deg, var(--primary), var(--accent));
  }

  .nutrition-card th{
    padding: 14px 12px;
    text-align: left;
    font-weight: 600;
    color: black;
    font-size: 14px;
  }

  .nutrition-card td{
    padding: 12px 12px;
    font-size: 14px;
    color: black;
  }

  .nutrition-card tr:nth-child(even) td{
    background: rgba(255,255,255,.03);
  }

  .nutrition-card tr:hover td{
    background: rgba(56,189,248,.06);
    transition: background 0.2s ease;
  }

  .nutrition-note{
    margin-top: 16px;
    font-size: 14px;
    color: var(--muted);
    line-height: 1.5;
  }
</style>
