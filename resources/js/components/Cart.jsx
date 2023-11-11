import React, { Component } from "react";
import ReactDOM from "react-dom";
import axios from "axios";
import Swal from "sweetalert2";
import { sum } from "lodash";
import Select from "react-select";

class Cart extends Component {
    constructor(props) {
        super(props);
        this.state = {
            cart: [],
            poin: 0,
            products: [],
            customers: [],
            kondisi: {
                jmlPembagi: 0,
                jmlMin: 0,
                minPoin: 0,
                maxPoin: 0,
                isCond: 0,
            },
            barcode: "",
            search: "",
            customer_id: "",
        };
        this.loadCart = this.loadCart.bind(this);
        this.handleOnChangeBarcode = this.handleOnChangeBarcode.bind(this);
        this.handleScanBarcode = this.handleScanBarcode.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyCart = this.handleEmptyCart.bind(this);

        this.loadProducts = this.loadProducts.bind(this);
        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.handleSeach = this.handleSeach.bind(this);
        this.setCustomerId = this.setCustomerId.bind(this);
        this.handleClickSubmit = this.handleClickSubmit.bind(this);
    }

    componentDidMount() {
        this.loadCart();
        this.loadProducts();
        this.loadCustomers();
    }

    loadCustomers() {
        axios.get(`/admin/customers`).then((res) => {
            const customers = res.data;
            this.setState({ customers });
        });
    }

    loadProducts(search = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then((res) => {
            const products = res.data.data;
            this.setState({ products });
        });
    }

    handleOnChangeBarcode(event) {
        const barcode = event.target.value;
        console.log(barcode);
        this.setState({ barcode });
    }

    loadCart() {
        axios.get("/admin/cart").then((res) => {
            const cart = res.data;
            this.setState({ cart });
        });
    }

    handleScanBarcode(event) {
        event.preventDefault();
        const { barcode } = this.state;
        if (!!barcode) {
            axios
                .post("/admin/cart", { barcode })
                .then((res) => {
                    this.loadCart();
                    this.setState({ barcode: "" });
                })
                .catch((err) => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }
    handleChangeQty(product_id, qty) {
        const cart = this.state.cart.map((c) => {
            if (c.id === product_id) {
                c.pivot.price = qty;
            }
            return c;
        });

        this.setState({ cart });
        if (!qty) return;

        axios
            .post("/admin/cart/change-qty", { product_id, price: qty })
            .then((res) => {})
            .catch((err) => {
                Swal.fire("Error!", err.response.data.message, "error");
            });
    }

    getTotal(cart) {
        console.log(this.state);

        const total = cart.map((c) => c.pivot.price * c.price);
        const totalDivideRegis = this.state.kondisi.jmlPembagi;
        const totalMinRegis = this.state.kondisi.jmlMin;
        const totalMinPoin = this.state.kondisi.minPoin;
        const totalCart = sum(total);
        const maxPoin = this.state.kondisi.maxPoin;
        const isCond = this.state.kondisi.isCond;
        const accumulate = (totalCart / totalMinRegis) % 1 === 0;

        let calcPoin = 0;

        if (this.state.customer_id) {
            if (accumulate && isCond) {
                // console.log("HEHE1");

                calcPoin =
                    totalCart / totalDivideRegis +
                    Math.floor(totalCart / totalMinRegis);
            } else {
                if (isCond) {
                    const calcSumPoin =
                        Math.floor(totalCart / totalMinRegis) * totalMinRegis;
                    if (calcSumPoin !== totalCart) {
                        calcPoin =
                            calcSumPoin / totalDivideRegis +
                            Math.floor(totalCart / totalMinRegis);
                    }
                }
                if (!isCond) {
                    console.log(totalCart, totalDivideRegis);
                    calcPoin = Math.floor(totalCart / totalMinRegis);
                }
            }
        }
        console.log(calcPoin);
        this.state.poin = calcPoin > maxPoin ? maxPoin : calcPoin;
        return sum(total).toFixed();
    }

    handleClickDelete(product_id) {
        axios
            .post("/admin/cart/delete", { product_id, _method: "DELETE" })
            .then((res) => {
                const cart = this.state.cart.filter((c) => c.id !== product_id);
                this.setState({ cart });
            });
    }
    handleEmptyCart() {
        axios.post("/admin/cart/empty", { _method: "DELETE" }).then((res) => {
            this.setState({ cart: [] });
        });
    }
    handleChangeSearch(event) {
        const search = event.target.value;
        this.setState({ search });
    }
    handleSeach(event) {
        if (event.keyCode === 13) {
            this.loadProducts(event.target.value);
        }
    }

    addProductToCart(barcode) {
        let product = this.state.products.find((p) => p.barcode === barcode);
        if (!!product) {
            let cart = this.state.cart.find((c) => c.id === product.id);
            if (!!cart) {
                this.setState({
                    cart: this.state.cart.map((c) => {
                        if (
                            c.id === product.id &&
                            product.quantity > c.pivot.quantity
                        ) {
                            c.pivot.quantity = c.pivot.quantity + 0;
                        }
                        return c;
                    }),
                });
            } else {
                if (product.quantity > 0) {
                    product = {
                        ...product,
                        pivot: {
                            quantity: 0,
                            product_id: product.id,
                            user_id: 1,
                        },
                    };

                    this.setState({ cart: [...this.state.cart, product] });
                }
            }

            axios
                .post("/admin/cart", { barcode })
                .then((res) => {
                    this.loadCart();
                })
                .catch((err) => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }

    setCustomerId(selectedOption) {
        const customerId = selectedOption.value;
        const customer = this.state.customers.find(
            (cust) => cust.id === customerId
        );
        if (customer) {
            this.setState({
                customer_id: customerId,
                kondisi: {
                    jmlPembagi: +customer.kondisi1.jmlPembagi,
                    jmlMin: +customer.kondisi1.jmlMin,
                    minPoin: +customer.kondisi1.minPoin,
                    maxPoin: +customer.kondisi1.maxPoin,
                    isCond: +customer.kondisi1.isCond,
                },
            });
        }
    }

    handleClickSubmit() {
        Swal.fire({
            title: "Received Amount",
            input: "text",
            inputValue: this.getTotal(this.state.cart),
            showCancelButton: true,
            confirmButtonText: "Send",
            showLoaderOnConfirm: true,
            preConfirm: (amount) => {
                return axios
                    .post("/admin/orders", {
                        customer_id: this.state.customer_id,
                        amount,
                        poin: this.state.poin,
                    })
                    .then((res) => {
                        this.loadCart();
                        return res.data;
                    })
                    .catch((err) => {
                        Swal.showValidationMessage(err.response.data.message);
                    });
            },
            allowOutsideClick: () => !Swal.isLoading(),
        }).then((result) => {
            if (result.value) {
                //
            }
        });
        const cust = this.state.customers.map((customer) => customer.nama);
        console.log(cust);
    }
    formatCurrency(amount) {
        const numericString = amount.toString().replace(/\D/g, "");

        const numericValue = parseInt(numericString, 10);

        return numericValue.toLocaleString("id-ID");
    }

    handleCheckboxChange(event) {
        const newValue = event.target.checked;
        this.setState({ checkboxValue: newValue });
    }

    render() {
        const { cart, products, customers, selectedCustomerId } = this.state;

        const customerOptions = customers.map((cus) => ({
            value: cus.id,
            label: `${cus.nama}-${cus.hp}-(${cus.kondisi1.namaKond})`,
        }));

        const redirectToCustomerAdd = () => {
            window.location.href = "/admin/customers/create";
        };

        return (
            <div className="row">
                <div className="col-md-6 col-lg-4">
                    <div className="row mb-2">
                        <div className="col">
                            <div className="d-flex justify-content-between align-items-center">
                                <div style={{ flex: 1, marginRight: "10px" }}>
                                    <Select
                                        options={customerOptions}
                                        value={customerOptions.find(
                                            (option) =>
                                                option.value ===
                                                selectedCustomerId
                                        )}
                                        onChange={this.setCustomerId}
                                        placeholder="Select Customer"
                                    />
                                </div>
                                <button
                                    onClick={redirectToCustomerAdd}
                                    className="btn btn-success btn-sm"
                                >
                                    Add Customer
                                    <i className="fas fa-add"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div className="user-cart">
                        <div className="card">
                            <table className="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {cart.map((c, i) => (
                                        <tr key={i}>
                                            <td>{c.name}</td>
                                            <td>
                                                <input
                                                    type="text"
                                                    className="form-control form-control-sm"
                                                    value={c.pivot.price}
                                                    onChange={(event) =>
                                                        this.handleChangeQty(
                                                            c.id,
                                                            event.target.value
                                                        )
                                                    }
                                                />
                                            </td>
                                            <td className="text-center">
                                                {window.APP.currency_symbol}{" "}
                                                {(
                                                    c.price * c.pivot.price
                                                ).toLocaleString({
                                                    style: "currency",
                                                    currency: "IDR",
                                                })}
                                            </td>
                                            <td>
                                                <button
                                                    className="btn btn-danger btn-sm"
                                                    onClick={() =>
                                                        this.handleClickDelete(
                                                            c.id
                                                        )
                                                    }
                                                >
                                                    <i className="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col">Total:</div>
                        <div className="col text-right">
                            {this.formatCurrency(this.getTotal(cart))}
                        </div>
                    </div>

                    <div className="row">
                        <div className="col">Poin:</div>
                        <div className="col text-right">{this.state.poin}</div>
                    </div>

                    <div className="row">
                        <div className="col">
                            <button
                                type="button"
                                className="btn btn-danger btn-block"
                                onClick={this.handleEmptyCart}
                                disabled={!cart.length}
                            >
                                Cancel
                            </button>
                        </div>
                        <div className="col">
                            <button
                                type="button"
                                className="btn btn-primary btn-block"
                                disabled={!cart.length}
                                onClick={this.handleClickSubmit}
                            >
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
                <div className="col-md-6 col-lg-8">
                    <div className="mb-2">
                        <input
                            type="text"
                            className="form-control"
                            placeholder="Search Tenant..."
                            onChange={this.handleChangeSearch}
                            onKeyDown={this.handleSeach}
                        />
                    </div>
                    <div className="order-product">
                        {products.map((p) => (
                            <div
                                onClick={() => this.addProductToCart(p.barcode)}
                                key={p.id}
                                className="item"
                            >
                                <img src={p.image_url} alt="" />
                                <h5
                                    style={
                                        window.APP.warning_quantity > p.quantity
                                            ? { color: "red" }
                                            : {}
                                    }
                                >
                                    {p.name}
                                </h5>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        );
    }
}

export default Cart;

if (document.getElementById("cart")) {
    ReactDOM.render(<Cart />, document.getElementById("cart"));
}
