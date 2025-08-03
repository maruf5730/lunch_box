<!-- Form modal, JS, and success modal -->

<style>
.modal {
  position: fixed;
  z-index: 999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.5);
}

.modal-content {
  background-color: #fff;
  margin: 8% auto;
  padding: 25px;
  border: 1px solid #888;
  width: 90%;
  max-width: 450px;
  border-radius: 10px;
  text-align: left;
}

.modal-content h3 {
  margin-top: 0;
}

.modal-content input,
.modal-content textarea {
  width: 100%;
  margin-bottom: 15px;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

.modal-content button {
  width: 100%;
  padding: 10px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.close-btn {
  float: right;
  font-size: 20px;
  cursor: pointer;
  color: #999;
}
</style>

<script>
function openModal(packageName) {
  document.getElementById('subscriptionModal').style.display = 'block';
  document.getElementById('modalPackageName').value = packageName;
  document.getElementById('selectedPackageName').innerText = packageName;
}

function closeModal() {
  document.getElementById('subscriptionModal').style.display = 'none';
  const successModal = document.getElementById('successModal');
  if (successModal) successModal.style.display = 'none';
}
</script>

<!-- Subscription Buttons Section -->
<section class="packages">
  <h2>Subscription Packages</h2>
  <div class="package-card">
    <h3>Daily Package – 60 BDT</h3>
    <button type="button" onclick="openModal('Daily')">Subscribe Now</button>
  </div>
  <div class="package-card">
    <h3>Weekly Package – 400 BDT</h3>
    <button type="button" onclick="openModal('Weekly')">Subscribe Now</button>
  </div>
  <div class="package-card">
    <h3>Monthly Package – 1600 BDT</h3>
    <p class="discount">10% off → 1440 BDT</p>
    <p class="discount">First-time purchase: Extra 5% off → 1368 BDT</p>
    <button type="button" onclick="openModal('Monthly')">Subscribe Now</button>
  </div>
</section>

<!-- Modal -->
<div id="subscriptionModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <h3>Subscribe to <span id="selectedPackageName"></span> Package</h3>
    <form method="POST">
      <input type="hidden" name="package_name" id="modalPackageName" />
      <label for="user_name">Full Name</label>
      <input type="text" name="user_name" required>
      <label for="email">Email</label>
      <input type="email" name="email" required>
      <label for="phone">Phone</label>
      <input type="text" name="phone" required>
      <label for="address">Address</label>
      <textarea name="address" required></textarea>
      <button type="submit">Confirm Subscription</button>
    </form>
  </div>
</div>

<!-- Show success or error -->
<?php if (!empty($_SESSION['success'])): ?>
  <div id="successModal" class="modal" style="display:block;">
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal()">&times;</span>
      <h3>Thank You!</h3>
      <p>You have successfully taken a package.</p>
    </div>
  </div>
  <?php unset($_SESSION['success']); ?>
<?php elseif (!empty($_SESSION['error'])): ?>
  <div id="successModal" class="modal" style="display:block;">
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal()">&times;</span>
      <h3>Error!</h3>
      <p>There was a problem: <?= htmlspecialchars($_SESSION['error']) ?></p>
    </div>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>
