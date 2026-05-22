const formatter = new Intl.NumberFormat("id-ID", {
  style: "currency",
  currency: "IDR",
  maximumFractionDigits: 0,
});

const inputs = [...document.querySelectorAll("input[data-price]")];
const totalNode = document.querySelector("#cartTotal");
const previewNode = document.querySelector("#cartPreview");
const stepButtons = [...document.querySelectorAll("[data-step]")];

function updateCart() {
  let total = 0;
  const rows = [];

  inputs.forEach((input) => {
    const quantity = Math.max(0, Number(input.value || 0));
    const price = Number(input.dataset.price || 0);

    if (quantity > 0) {
      const subtotal = quantity * price;
      total += subtotal;
      rows.push({ name: input.dataset.name, quantity, subtotal });
    }
  });

  if (totalNode) {
    totalNode.textContent = formatter.format(total).replace(/\s/g, " ");
  }

  if (previewNode) {
    previewNode.innerHTML = rows.length
      ? rows
          .map((row) => `<li><span>${row.quantity}x ${row.name}</span><strong>${formatter.format(row.subtotal).replace(/\s/g, " ")}</strong></li>`)
          .join("")
      : `<li class="empty-cart">Belum ada menu dipilih.</li>`;
  }
}

inputs.forEach((input) => input.addEventListener("input", updateCart));
stepButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const input = button.parentElement?.querySelector("input[data-price]");

    if (!input) {
      return;
    }

    const step = Number(button.dataset.step || 0);
    const current = Math.max(0, Number(input.value || 0));
    const next = Math.min(99, Math.max(0, current + step));
    input.value = String(next);
    input.dispatchEvent(new Event("input", { bubbles: true }));
  });
});
updateCart();
