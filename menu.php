<!-- menu.php -->
<section class="menu-table">
  <h2>Daily Food Menu <span style="color:var(--accent)">(for Custom Order)</span></h2>
  <div class="menu-flex">
    <table>
      <thead>
        <tr>
          <th>Day</th>
          <th>Menu</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>Monday</td><td>Rice, Lentils, Egg Curry, Mixed Vegetables</td></tr>
        <tr><td>Tuesday</td><td>Khichuri, Chicken Curry, Salad</td></tr>
        <tr><td>Wednesday</td><td>Rice, Fish Curry, Beans, Tomato Chutney</td></tr>
        <tr><td>Thursday</td><td>Vegetable Pulao, Egg Masala, Raita</td></tr>
        <tr><td>Friday</td><td>Plain Rice, Beef Curry, Spinach</td></tr>
      </tbody>
    </table>

    <div class="menu-img-wrap">
      <img class="menu-food-img" 
        src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80" 
        alt="Lunch Food Sample">
    </div>
    <div class="menu-img-wrap">
      <img class="menu-food-img" 
        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQyyXhJdM2zbTSw6lVRMCyKeYsMzFOPN-1Isg&s" 
        alt="Lunch Food Sample">
    </div>
    <div class="menu-img-wrap">
      <img class="menu-food-img" 
        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTXYjhrityydBOxomBmvNOIKRNKQ4ASdktLwQ&s" 
        alt="Lunch Food Sample">
    </div>
  </div>
</section>

<style>
  :root{
    --bg:#0f172a;
    --card:#0b1227;
    --muted:#64748b;
    --text:#e2e8f0;
    --primary:#22c55e;
    --ring:#86efac;
    --border:#1e293b;
    --danger:#ef4444;
    --accent:#38bdf8;
  }

  .menu-table{
    max-width:1100px;
    margin: 60px auto;
    padding: 20px;
    background: linear-gradient(180deg, rgba(254, 254, 255, 0.9), rgba(255, 255, 255, 0.9));
    border: 1px solid var(--border);
    border-radius: 18px;
    backdrop-filter: blur(8px);
    color: var(--text);
  }
  .menu-table h2{
    text-align:center;
    font-size: clamp(22px, 2vw, 28px);
    margin-bottom: 24px;
  }
  .menu-flex{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    align-items: start;
  }
  @media(max-width:900px){
    .menu-flex{ grid-template-columns: 1fr }
  }

  .menu-table table{
    width:100%;
    border-collapse: collapse;
    border-radius: 12px;
    overflow:hidden;
    background: #ffffffff;
    box-shadow: 0 8px 24px rgba(0,0,0,.3);
  }
  .menu-table th, .menu-table td{
    padding: 14px 16px;
    text-align: left;
  }
  .menu-table th{
    background: white;
    color: black;
    font-weight: 600;
    font-size: 15px;
  }
  .menu-table tr:nth-child(even) td{
    background: rgba(248, 248, 248, 0.03);
  }
  .menu-table tr:hover td{
    background: rgba(56,189,248,.06);
  }
  .menu-table td{
    font-size: 14px;
    color: black;
  }

  .menu-img-wrap{
    border-radius: 14px;
    overflow:hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,.35);
    align-self: center;
  }
  .menu-food-img{
    display:block;
    width:100%;
    height:100%;
    object-fit: cover;
    transition: transform .3s ease;
  }
  .menu-food-img:hover{
    transform: scale(1.05);
  }
</style>
