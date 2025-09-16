import { Head } from '@inertiajs/react';
import Layout from '@/Components/Layout';
import { useState } from 'react';

export default function Index({ products }) {
    const [selectedCategory, setSelectedCategory] = useState('all');
    
    const categories = ['all', ...new Set(products.map(p => p.category))];
    
    const filteredProducts = selectedCategory === 'all' 
        ? products 
        : products.filter(p => p.category === selectedCategory);

    return (
        <Layout title="Products">

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Category Filter */}
                    <div className="mb-8">
                        <div className="flex flex-wrap gap-2">
                            {categories.map(category => (
                                <button
                                    key={category}
                                    onClick={() => setSelectedCategory(category)}
                                    className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                                        selectedCategory === category
                                            ? 'bg-blue-600 text-white'
                                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                                    }`}
                                >
                                    {category === 'all' ? 'All Products' : category}
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Products Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {filteredProducts.map((product) => (
                            <div key={product.id} className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                <div className="p-6">
                                    <div className="mb-4">
                                        <h3 className="text-lg font-semibold text-gray-900 mb-2">
                                            {product.name}
                                        </h3>
                                        <p className="text-sm text-gray-600 mb-2">
                                            {product.subcategory && (
                                                <span className="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2">
                                                    {product.subcategory}
                                                </span>
                                            )}
                                            <span className="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                {product.category}
                                            </span>
                                        </p>
                                    </div>
                                    
                                    <p className="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {product.description}
                                    </p>
                                    
                                    <div className="mb-4">
                                        <span className="text-2xl font-bold text-blue-600">
                                            From ${product.base_price}
                                        </span>
                                    </div>
                                    
                                    <div className="mb-4">
                                        <h4 className="text-sm font-medium text-gray-900 mb-2">Pricing Options:</h4>
                                        <div className="space-y-1">
                                            {JSON.parse(product.pricing_options).map((option, index) => (
                                                <div key={index} className="flex justify-between text-sm">
                                                    <span>{option.quantity} {option.quantity === 1 ? 'piece' : 'pieces'}</span>
                                                    <span className="font-medium">${option.price}</span>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                    
                                    <div className="flex gap-2">
                                        <button className="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            Get Quote
                                        </button>
                                        <button className="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                            View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </Layout>
    );
}
