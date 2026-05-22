const formatter = new Intl.NumberFormat("id-ID", {
  style: "currency",
  currency: "IDR",
  maximumFractionDigits: 0,
});

const inputs = [...document.querySelectorAll("input[data-price]")];
const totalNode = document.querySelector("#cartTotal");
const previewNode = document.querySelector("#cartPreview");

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
    previewNode.innerHTML = rows
      .map((row) => `<li><span>${row.quantity}x ${row.name}</span><strong>${formatter.format(row.subtotal).replace(/\s/g, " ")}</strong></li>`)
      .join("");
  }
}

inputs.forEach((input) => input.addEventListener("input", updateCart));
updateCart();
